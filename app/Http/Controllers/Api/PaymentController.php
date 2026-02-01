<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use App\Services\FawaterkService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    use \App\Traits\HandlesPayments;

    protected $fawaterkService;

    public function __construct(FawaterkService $fawaterkService)
    {
        $this->fawaterkService = $fawaterkService;
    }

    /**
     * Get user payments.
     */
    public function getUserPayments(Request $request): JsonResponse
    {
        $query = Payment::query()
            ->where(function ($q) {
                // Ensure the payment belongs to the authenticated user
                // This assumes all payable models have a user_id or similar owner field
                // For safety, we can filter model-wise or rely on a standard user_id
                $q->whereHasMorph('payable', [Booking::class, \App\Models\ServiceRequest::class, \App\Models\EventTicket::class], function ($query) {
                    $query->where('user_id', Auth::id());
                });
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payable
        if ($request->filled('payable_id') && $request->filled('payable_type')) {
            $type = $this->resolvePayableType($request->payable_type);
            if ($type) {
                $query->where('payable_id', $request->payable_id)
                      ->where('payable_type', $type);
            }
        }

        $payments = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($payment) {
                $payableData = null;
                if ($payment->payable) {
                    $type = class_basename($payment->payable);
                    $reference = match($type) {
                        'Booking' => $payment->payable->booking_reference,
                        'ServiceRequest' => $payment->payable->request_reference,
                        'EventTicket' => $payment->payable->ticket_reference,
                        default => '#' . $payment->payable->id
                    };

                    $name = '';
                    if ($type === 'Booking' && $payment->payable->hotel) {
                        $name = app()->getLocale() === 'ar' ? $payment->payable->hotel->name_ar : $payment->payable->hotel->name_en;
                    } elseif ($type === 'ServiceRequest') {
                         $name = $payment->payable->service_type === 'bus' ? 'Bus Service' : 'Private Car';
                    } elseif ($type === 'EventTicket' && $payment->payable->event) {
                         $name = app()->getLocale() === 'ar' ? $payment->payable->event->name_ar : $payment->payable->event->name_en;
                    }

                    $payableData = [
                        'id' => $payment->payable->id,
                        'type' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $type)),
                        'reference' => $reference,
                        'name' => $name,
                    ];
                }

                return [
                    'id' => $payment->id,
                    'payable' => $payableData,
                    'amount' => (float) $payment->amount,
                    'payment_method' => $payment->payment_method,
                    'status' => $payment->status,
                    'transaction_id' => $payment->transaction_id,
                    'notes' => $payment->notes,
                    'paid_at' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : null,
                    'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $payments,
        ]);
    }

    /**
     * Resolve string type to full class name.
     */
    protected function resolvePayableType($type)
    {
        return match($type) {
            'booking' => Booking::class,
            'service_request' => \App\Models\ServiceRequest::class,
            'event_ticket' => \App\Models\EventTicket::class,
            default => null
        };
    }

    /**
     * Get payment details.
     */
    public function getPaymentDetails(Payment $payment): JsonResponse
    {
        $payment->load('payable');

        if (!$payment->payable || $payment->payable->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('api.payments.unauthorized'),
            ], 403);
        }

        $type = class_basename($payment->payable);
        $reference = match($type) {
            'Booking' => $payment->payable->booking_reference,
            'ServiceRequest' => $payment->payable->request_reference,
            'EventTicket' => $payment->payable->ticket_reference,
            default => '#' . $payment->payable->id
        };

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'payable' => [
                    'id' => $payment->payable->id,
                    'type' => strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $type)),
                    'reference' => $reference,
                    'total_price' => (float) $payment->payable->total_price,
                    'total_paid' => (float) $payment->payable->total_paid,
                    'remaining_amount' => (float) $payment->payable->remaining_amount,
                ],
                'amount' => (float) $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'transaction_id' => $payment->transaction_id,
                'notes' => $payment->notes,
                'paid_at' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : null,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Create a payment for a booking.
     */
    public function createPayment(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'payable_type' => 'required|in:booking,service_request,event_ticket',
                'payable_id' => 'required|integer',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|in:cash,card,bank_transfer,online,other',
                'transaction_id' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            $modelClass = $this->resolvePayableType($validated['payable_type']);
            if (!$modelClass) {
                return response()->json(['success' => false, 'message' => 'Invalid payable type'], 400);
            }

            $payable = $modelClass::findOrFail($validated['payable_id']);

            // Check authorization
            if ($payable->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.payments.unauthorized'),
                ], 403);
            }

            // Check remaining amount
            $remainingAmount = $payable->remaining_amount;
            if ($validated['amount'] > $remainingAmount) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.payments.amount_exceeds_remaining', [
                        'remaining' => number_format($remainingAmount, 2),
                    ]),
                ], 400);
            }

            // Initiate Payment via Trait
            $paymentData = $this->initiatePayment(
                $payable, 
                $validated['amount'], 
                $validated['payment_method'], 
                $request->payment_method_id, 
                $validated['notes']
            );

            // Notify Admins
            app(\App\Services\FirebaseNotificationService::class)->sendToAdmins(
                "عملية دفع جديدة",
                "قام المستخدم ({$payable->user->name}) بإضافة عملية دفع بقيمة ({$validated['amount']}).",
                "new_payment",
                [
                    'payment_id' => $paymentData['payment_id'], 
                    'payable_type' => $validated['payable_type'], 
                    'payable_id' => $payable->id, 
                    'amount' => $validated['amount']
                ]
            );

            return response()->json([
                'success' => true,
                'message' => __('api.payments.created'),
                'data' => array_merge([
                    'remaining_amount' => (float) ($remainingAmount - $validated['amount']),
                ], $paymentData),
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Verify payment status manually.
     */
    public function verifyPayment(Request $request): JsonResponse
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'invoice_id' => 'required|string',
        ]);

        $payment = Payment::findOrFail($request->payment_id);
        
        try {
            $statusData = $this->fawaterkService->checkInvoiceStatus($request->invoice_id);
            
            // Check status in response (Adjust based on actual API response structure)
            // Assuming 'paid' boolean or 'status' string
            $isPaid = false;
            $status = 'pending';
            
            if (isset($statusData['data']['users_res']['invoice_status'])) {
                 $status = $statusData['data']['users_res']['invoice_status'];
                 if ($status === 'paid') {
                     $isPaid = true;
                 }
            }
            
            if ($isPaid && $payment->status !== 'completed') {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                    'notes' => $payment->notes . "\nVerified Success from API",
                ]);
            }
            
            return response()->json([
                'success' => true,
                'data' => [
                   'status' => $payment->status,
                   'invoice_status' => $status
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Verification failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function paymentSuccess(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        
        if ($invoiceId) {
            $payment = Payment::where('transaction_id', $invoiceId)->first();
            if ($payment && $payment->status !== 'completed') {
                $payment->update([
                    'status' => 'completed',
                    'paid_at' => now(),
                ]);
            }
        }
        
        // You might want to return a view here instead of JSON for the browser redirect
        return response()->json(['message' => 'Payment Successful', 'invoice_id' => $invoiceId]);
    }

    public function paymentFail(Request $request)
    {
        $invoiceId = $request->query('invoice_id');
        if ($invoiceId) {
            Payment::where('transaction_id', $invoiceId)->update(['status' => 'failed']);
        }
        
        return response()->json(['message' => 'Payment Failed', 'invoice_id' => $invoiceId]);
    }
    
    public function paymentPending(Request $request)
    {
        return response()->json(['message' => 'Payment Pending']); 
    }

    /**
     * Handle Fawaterk Webhook (IPN).
     */
    public function webhook(Request $request): JsonResponse
    {
        Log::info('Fawaterk Webhook Received:', $request->all());

        $invoiceId = $request->input('invoice_id');
        $status = $request->input('payment_status') ?? $request->input('invoice_status'); // Handle both common field names

        if (!$invoiceId) {
            return response()->json(['success' => false, 'message' => 'Missing invoice_id'], 400);
        }

        $payment = Payment::where('transaction_id', $invoiceId)->first();

        if (!$payment) {
            Log::warning("Fawaterk Webhook: Payment not found for Invoice ID: $invoiceId");
            return response()->json(['success' => false, 'message' => 'Payment not found'], 404);
        }

        if ($status === 'paid' && $payment->status !== 'completed') {
            $payment->update([
                'status' => 'completed',
                'paid_at' => now(),
                'notes' => $payment->notes . "\nWebhook: Paid successfully."
            ]);
            Log::info("Payment #{$payment->id} marked as success via webhook.");
        } elseif ($status === 'failed') {
            $payment->update([
                'status' => 'failed',
                'notes' => $payment->notes . "\nWebhook: Payment failed."
            ]);
            Log::info("Payment #{$payment->id} marked as failed via webhook.");
        }

        return response()->json(['success' => true]);
    }
}
