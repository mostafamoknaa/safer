<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class BookingController extends Controller
{
    /**
     * Get user bookings.
     */
    public function getUserBookings(Request $request): JsonResponse
    {
        $query = Booking::with(['hotel', 'hotel.province', 'room'])
            ->where('user_id', Auth::id());

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by hotel
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $bookings = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($booking) {
                return [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'hotel' => $booking->hotel ? [
                        'id' => $booking->hotel->id,
                        'name' => app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en,
                        'province' => $booking->hotel->province ? [
                            'name' => app()->getLocale() === 'ar' ? $booking->hotel->province->name_ar : $booking->hotel->province->name_en,
                        ] : null,
                    ] : null,
                    'room' => $booking->room ? [
                        'id' => $booking->room->id,
                        'price_per_night' => (float) $booking->room->price_per_night,
                        'beds_count' => $booking->room->beds_count,
                    ] : null,
                    'check_in_date' => $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : null,
                    'check_out_date' => $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : null,
                    'nights_count' => $booking->nights_count,
                    'guests_count' => $booking->guests_count,
                    'rooms_count' => $booking->rooms_count,
                    'price_per_night' => $booking->price_per_night ? (float) $booking->price_per_night : null,
                    'total_price' => (float) $booking->total_price,
                    'status' => $booking->status,
                    'total_paid' => (float) $booking->total_paid,
                    'remaining_amount' => (float) $booking->remaining_amount,
                    'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Get booking details.
     */
    public function getBookingDetails(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('api.bookings.unauthorized'),
            ], 403);
        }

        $booking->load(['hotel', 'hotel.province', 'room', 'room.media', 'payments']);

        $roomImages = $booking->room && $booking->room->media ? $booking->room->media->where('type', 'image')->map(function ($media) {
            return $media->file_url;
        })->values() : [];

        $payments = $booking->payments->map(function ($payment) {
            return [
                'id' => $payment->id,
                'amount' => (float) $payment->amount,
                'payment_method' => $payment->payment_method,
                'status' => $payment->status,
                'transaction_id' => $payment->transaction_id,
                'paid_at' => $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i:s') : null,
                'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $booking->id,
                'booking_reference' => $booking->booking_reference,
                'hotel' => $booking->hotel ? [
                    'id' => $booking->hotel->id,
                    'name' => app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $booking->hotel->address_ar : $booking->hotel->address_en,
                    'province' => $booking->hotel->province ? [
                        'name' => app()->getLocale() === 'ar' ? $booking->hotel->province->name_ar : $booking->hotel->province->name_en,
                    ] : null,
                    'website_url' => $booking->hotel->website_url,
                ] : null,
                'room' => $booking->room ? [
                    'id' => $booking->room->id,
                    'price_per_night' => (float) $booking->room->price_per_night,
                    'beds_count' => $booking->room->beds_count,
                    'bathrooms_count' => $booking->room->bathrooms_count,
                    'rooms_count' => $booking->room->rooms_count,
                    'images' => $roomImages,
                ] : null,
                'check_in_date' => $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : null,
                'check_out_date' => $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : null,
                'nights_count' => $booking->nights_count,
                'guests_count' => $booking->guests_count,
                'rooms_count' => $booking->rooms_count,
                'price_per_night' => $booking->price_per_night ? (float) $booking->price_per_night : null,
                'total_price' => (float) $booking->total_price,
                'status' => $booking->status,
                'notes' => $booking->notes,
                'total_paid' => (float) $booking->total_paid,
                'remaining_amount' => (float) $booking->remaining_amount,
                'payments' => $payments,
                'created_at' => $booking->created_at->format('Y-m-d H:i:s'),
            ],
        ]);
    }

    /**
     * Create a new booking.
     */
    public function createBooking(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'hotel_id' => 'required|exists:hotels,id',
                'room_id' => 'nullable|exists:hotel_rooms,id',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'guests_count' => 'required|integer|min:1|max:100',
                'rooms_count' => 'required|integer|min:1|max:50',
                'notes' => 'nullable|string|max:1000',
            ]);

            $hotel = Hotel::findOrFail($validated['hotel_id']);

            if (!$hotel->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.hotels.hotel_not_available'),
                ], 400);
            }

            // If room_id is provided, validate it belongs to the hotel
            $room = null;
            $pricePerNight = null;

            if ($validated['room_id']) {
                $room = HotelRoom::where('id', $validated['room_id'])
                    ->where('hotel_id', $hotel->id)
                    ->where('is_active', true)
                    ->first();

                if (!$room) {
                    return response()->json([
                        'success' => false,
                        'message' => __('api.hotels.room_not_available'),
                    ], 400);
                }

                $pricePerNight = $room->price_per_night;

                // Check room availability
                $conflictingBookings = Booking::where('room_id', $room->id)
                    ->where(function ($q) use ($validated) {
                        $q->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                            ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                            ->orWhere(function ($query) use ($validated) {
                                $query->where('check_in_date', '<=', $validated['check_in_date'])
                                    ->where('check_out_date', '>=', $validated['check_out_date']);
                            });
                    })
                    ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                    ->exists();

                if ($conflictingBookings) {
                    return response()->json([
                        'success' => false,
                        'message' => __('api.bookings.room_not_available_dates'),
                    ], 400);
                }
            } else {
                // If no room specified, use the minimum price from hotel rooms
                $pricePerNight = $hotel->rooms()->where('is_active', true)->min('price_per_night');
                if (!$pricePerNight) {
                    return response()->json([
                        'success' => false,
                        'message' => __('api.hotels.no_rooms_available'),
                    ], 400);
                }
            }

            // Calculate nights and total price
            $nights = max(1, \Carbon\Carbon::parse($validated['check_in_date'])->diffInDays($validated['check_out_date']));
            $totalPrice = $pricePerNight * $nights * $validated['rooms_count'];

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
                'room_id' => $validated['room_id'] ?? null,
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'guests_count' => $validated['guests_count'],
                'rooms_count' => $validated['rooms_count'],
                'price_per_night' => $pricePerNight,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('api.bookings.created'),
                'data' => [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'total_price' => $totalPrice,
                    'nights' => $nights,
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

    /**
     * Cancel a booking.
     */
    public function cancelBooking(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('api.bookings.unauthorized'),
            ], 403);
        }

        if (!in_array($booking->status, ['pending', 'confirmed'])) {
            return response()->json([
                'success' => false,
                'message' => __('api.bookings.cannot_cancel'),
            ], 400);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json([
            'success' => true,
            'message' => __('api.bookings.cancelled'),
        ]);
    }
}

