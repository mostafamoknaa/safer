<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\ServiceRequest;
use App\Models\EventTicket;
use App\Traits\HandlesPayments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    use HandlesPayments;

    /**
     * Initiate payment for a given model.
     * 
     * @param string $type (booking, service_request, event_ticket)
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function pay(Request $request, $type, $id)
    {
        $modelClass = match ($type) {
            'booking' => Booking::class,
            'service_request' => ServiceRequest::class,
            'event_ticket' => EventTicket::class,
            default => null
        };

        if (!$modelClass) {
            return back()->with('error', 'نوع الحجز غير صالح');
        }

        $payable = $modelClass::findOrFail($id);

        // Authorization check
        if ($payable->user_id !== Auth::id()) {
            abort(403);
        }

        // Status check - only confirmed/approved bookings can be paid
        $allowedStatuses = ['confirmed', 'approved', 'active'];
        if (!in_array($payable->status, $allowedStatuses)) {
            return back()->with('error', 'لا يمكن دفع هذا الحجز في حالته الحالية');
        }

        if ($payable->isFullyPaid()) {
            return back()->with('info', 'تم دفع هذا الحجز بالكامل مسبقاً');
        }

        // If payment method is not selected yet, show the selection page
        if (!$request->has('payment_method_id')) {
            return view('web.payments.select_method', [
                'payable' => $payable,
                'type' => $type,
                'amount' => (float) $payable->remaining_amount
            ]);
        }

        try {
            // Link to the primary service without changing it
            $fawaterkService = app(\App\Services\FawaterkService::class);

            $fawaterkType = class_basename($payable);
            $reference = match ($fawaterkType) {
                'Booking' => $payable->booking_reference,
                'ServiceRequest' => $payable->request_reference,
                'EventTicket' => $payable->ticket_reference,
                default => "#{$payable->id}"
            };

            // Use the selected payment method ID
            $paymentMethodId = $request->input('payment_method_id', 2); // Default to Credit Card if somehow missing

            // Use the service's existing override capability
            $fawaterkData = [
                'amount' => (float) $payable->remaining_amount,
                'user_name' => Auth::user()->name,
                'user_email' => Auth::user()->email,
                'user_phone' => Auth::user()->phone ?? '01000000000',
                'payment_method_id' => $paymentMethodId,
                'description' => "Payment for {$fawaterkType} #{$reference}",
                'redirectionUrls' => [
                    'successUrl' => route('web.payments.callback.success'),
                    'failUrl' => route('web.payments.callback.fail'),
                    'pendingUrl' => route('web.payments.callback.pending'),
                ],
            ];

            // 1. Create Payment Record (manual creation to avoid trait modifications)
            $payment = \App\Models\Payment::create([
                'payable_type' => get_class($payable),
                'payable_id' => $payable->id,
                'booking_id' => ($payable instanceof Booking) ? $payable->id : null,
                'amount' => (float) $payable->remaining_amount,
                'payment_method' => 'online',
                'status' => 'pending',
                'notes' => 'Web initiated payment via method #' . $paymentMethodId,
            ]);

            $result = $fawaterkService->executePayment($fawaterkData);
            \Illuminate\Support\Facades\Log::info('Web Payment Fawaterk Result:', $result);

            $redirectUrl = $result['data']['payment_data']['redirectTo']
                ?? $result['data']['invoice_link']
                ?? $result['data']['url']
                ?? null;

            if ($redirectUrl) {
                $payment->update([
                    'transaction_id' => $result['data']['invoice_id'] ?? null,
                    'notes' => $payment->notes . "\nFawaterk Invoice ID: " . ($result['data']['invoice_id'] ?? 'N/A')
                ]);

                return redirect($redirectUrl);
            }

            // Handle Fawry Code
            if (isset($result['data']['payment_data']['fawryCode'])) {
                $fawryCode = $result['data']['payment_data']['fawryCode'];
                $expireDate = $result['data']['payment_data']['expireDate'] ?? '';

                $payment->update([
                    'transaction_id' => $result['data']['invoice_id'] ?? null,
                    'notes' => $payment->notes . "\nFawry Code: $fawryCode\nExpires: $expireDate"
                ]);

                return $this->redirectWithStatus($payment, 'success', "يرجى الدفع باستخدام كود فوري: $fawryCode (صالح حتى $expireDate)");
            }

            // Handle Fawaterk Wallet/Other codes if applicable (assuming similar structure)
            if (isset($result['data']['payment_data']['meezaReference'])) {
                $meezaRef = $result['data']['payment_data']['meezaReference'];
                $payment->update([
                    'transaction_id' => $result['data']['invoice_id'] ?? null,
                    'notes' => $payment->notes . "\nWallet Reference: $meezaRef"
                ]);
                return $this->redirectWithStatus($payment, 'success', "يرجى الدفع باستخدام مرجع المحفظة الإلكترونية: $meezaRef");
            }

            return back()->with('error', 'فشلت عملية تهيئة الدفع، يرجى المحاولة مرة أخرى أو الاتصال بالدعم');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Web Payment Error: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ غير متوقع أثناء معالجة الدفع: ' . $e->getMessage());
        }
    }

    public function success(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $payment = \App\Models\Payment::where('transaction_id', $invoiceId)->first();

        if ($payment) {
            if ($payment->status !== 'completed') {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
            return $this->redirectWithStatus($payment, 'success', 'تمت عملية الدفع بنجاح');
        }

        return redirect()->route('web.home')->with('success', 'تمت عملية الدفع بنجاح');
    }

    public function fail(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $payment = \App\Models\Payment::where('transaction_id', $invoiceId)->first();

        if ($payment) {
            $payment->update(['status' => 'failed']);
            return $this->redirectWithStatus($payment, 'error', 'فشلت عملية الدفع، يرجى المحاولة مرة أخرى');
        }

        return redirect()->route('web.home')->with('error', 'فشلت عملية الدفع');
    }

    public function pending(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        $payment = \App\Models\Payment::where('transaction_id', $invoiceId)->first();

        if ($payment) {
            return $this->redirectWithStatus($payment, 'info', 'عملية الدفع قيد الانتظار');
        }

        return redirect()->route('web.home')->with('info', 'عملية الدفع قيد الانتظار');
    }

    private function redirectWithStatus($payment, $flashType, $message)
    {
        $payable = $payment->payable;

        $route = match (get_class($payable)) {
            Booking::class => route('web.bookings.show', $payable->id),
            ServiceRequest::class => route('web.bookings.service_show', $payable->id),
            EventTicket::class => route('web.bookings.event_ticket_show', $payable->id),
            default => route('web.home')
        };

        return redirect($route)->with($flashType, $message);
    }
}
