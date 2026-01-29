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
        $query = Payment::with(['booking', 'booking.hotel'])
            ->whereHas('booking', function ($q) {
                $q->where('user_id', Auth::id());
            });

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by booking
        if ($request->filled('booking_id')) {
            $query->where('booking_id', $request->booking_id);
        }

        $payments = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'booking' => $payment->booking ? [
                        'id' => $payment->booking->id,
                        'booking_reference' => $payment->booking->booking_reference,
                        'hotel' => $payment->booking->hotel ? [
                            'name' => app()->getLocale() === 'ar' ? $payment->booking->hotel->name_ar : $payment->booking->hotel->name_en,
                        ] : null,
                    ] : null,
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
     * Get payment details.
     */
    public function getPaymentDetails(Payment $payment): JsonResponse
    {
        if ($payment->booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('api.payments.unauthorized'),
            ], 403);
        }

        $payment->load(['booking', 'booking.hotel', 'booking.hotel.province']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $payment->id,
                'booking' => [
                    'id' => $payment->booking->id,
                    'booking_reference' => $payment->booking->booking_reference,
                    'hotel' => $payment->booking->hotel ? [
                        'id' => $payment->booking->hotel->id,
                        'name' => app()->getLocale() === 'ar' ? $payment->booking->hotel->name_ar : $payment->booking->hotel->name_en,
                        'address' => app()->getLocale() === 'ar' ? $payment->booking->hotel->address_ar : $payment->booking->hotel->address_en,
                    ] : null,
                    'total_price' => (float) $payment->booking->total_price,
                    'total_paid' => (float) $payment->booking->total_paid,
                    'remaining_amount' => (float) $payment->booking->remaining_amount,
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
                'booking_id' => 'required|exists:bookings,id',
                'amount' => 'required|numeric|min:0.01',
                'payment_method' => 'required|in:cash,card,bank_transfer,online,other',
                'transaction_id' => 'nullable|string|max:255',
                'notes' => 'nullable|string|max:1000',
            ]);

            $booking = Booking::findOrFail($validated['booking_id']);

            // Check authorization
            if ($booking->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.payments.unauthorized'),
                ], 403);
            }

            // Check remaining amount
            $remainingAmount = $booking->remaining_amount;
            if ($validated['amount'] > $remainingAmount) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.payments.amount_exceeds_remaining', [
                        'remaining' => number_format($remainingAmount, 2),
                    ]),
                ], 400);
            }

            // Create payment
            $payment = Payment::create([
                'booking_id' => $booking->id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'pending',
                'transaction_id' => $validated['transaction_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            $responseData = [
                'id' => $payment->id,
                'amount' => (float) $payment->amount,
                'remaining_amount' => (float) ($remainingAmount - $payment->amount),
            ];

            // Handle Online Payment via Fawaterk
            if ($validated['payment_method'] === 'online') {
                try {
                    $fawaterkData = [
                        'amount' => $payment->amount,
                        'user_name' => $booking->user->name,
                        'user_email' => $booking->user->email,
                        'user_phone' => $booking->user->phone ?? '01000000000', // Fallback or validate phone
                        'payment_method_id' => $request->input('payment_method_id', 3), // Default to card (check docs)
                        'description' => "Payment for Booking #{$booking->booking_reference}",
                    ];

                    $result = $this->fawaterkService->executePayment($fawaterkData);

                    if (isset($result['data']['payment_data']['redirectTo'])) {
                        $payment->update([
                            'transaction_id' => $result['data']['invoice_id'] ?? null, 
                            'notes' => 'Fawaterk Invoice ID: ' . ($result['data']['invoice_id'] ?? 'N/A')
                        ]);
                        
                        $responseData['payment_url'] = $result['data']['payment_data']['redirectTo'];
                    } elseif (isset($result['data']['payment_data']['fawryCode'])) {
                         $payment->update([
                            'transaction_id' => $result['data']['invoice_id'] ?? null, 
                            'notes' => 'Fawaterk Invoice ID: ' . ($result['data']['invoice_id'] ?? 'N/A') . "\nFawry Code: " . $result['data']['payment_data']['fawryCode']
                        ]);
                        
                        $responseData['fawry_code'] = $result['data']['payment_data']['fawryCode'];
                        $responseData['expire_date'] = $result['data']['payment_data']['expireDate'];
                        $responseData['message'] = 'Use this code to pay via Fawry';
                    } else {
                        // Fallback: just return available payment data
                        $payment->update([
                            'transaction_id' => $result['data']['invoice_id'] ?? null,
                            'notes' => 'Fawaterk Invoice ID: ' . ($result['data']['invoice_id'] ?? 'N/A')
                        ]);
                        $responseData['payment_data'] = $result['data']['payment_data'] ?? [];
                    }

                } catch (\Exception $e) {
                    $payment->update(['status' => 'failed', 'notes' => 'Failed to init online payment: ' . $e->getMessage()]);
                    Log::error("Fawaterk Init Error: " . $e->getMessage());

                    return response()->json([
                        'success' => false,
                        'message' => 'Online payment initiation failed.',
                        'error' => $e->getMessage(),
                    ], 500);
                }
            }

            // Notify Admins
            app(\App\Services\FirebaseNotificationService::class)->sendToAdmins(
                "عملية دفع جديدة",
                "قام المستخدم ({$booking->user->name}) بإضافة عملية دفع بقيمة ({$payment->amount}) للحجز ({$booking->booking_reference}).",
                "new_payment",
                ['payment_id' => $payment->id, 'booking_id' => $booking->id, 'amount' => $payment->amount]
            );

            return response()->json([
                'success' => true,
                'message' => __('api.payments.created'),
                'data' => $responseData,
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
            
            if ($isPaid && $payment->status !== 'success') {
                $payment->update([
                    'status' => 'success',
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
            if ($payment && $payment->status !== 'success') {
                $payment->update([
                    'status' => 'success',
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
}
