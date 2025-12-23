<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
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

            return response()->json([
                'success' => true,
                'message' => __('api.payments.created'),
                'data' => [
                    'id' => $payment->id,
                    'amount' => (float) $payment->amount,
                    'remaining_amount' => (float) ($remainingAmount - $payment->amount),
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        }
    }
}

