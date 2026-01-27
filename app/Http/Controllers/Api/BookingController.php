<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingRoom;
use App\Models\Hotel;
use App\Models\EventTicket;
use App\Models\Event;
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
        $query = Booking::with([
            'hotel', 'hotel.province', 'hotel.media', 'hotel.user',
            'bookedRooms.room', 'bookedRooms.room.media'
        ])->where('user_id', Auth::id());

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
                $hotelImages = $booking->hotel && $booking->hotel->media ? 
                    $booking->hotel->media->where('type', 'image')->map(function ($media) {
                        return [
                            'url' => $media->file_url,
                            'order' => $media->order_column,
                        ];
                    })->sortBy('order')->take(3)->values() : [];

                $rooms = $booking->bookedRooms->map(function ($bookedRoom) {
                    $roomImages = $bookedRoom->room && $bookedRoom->room->media ? 
                        $bookedRoom->room->media->where('type', 'image')->map(function ($media) {
                            return [
                                'url' => $media->file_url,
                                'order' => $media->order_column,
                            ];
                        })->sortBy('order')->values() : [];

                    return [
                        'id' => $bookedRoom->room->id,
                        'name' => $bookedRoom->room->name ?? 'Room ' . $bookedRoom->room->id,
                        'type' => $bookedRoom->room->type ?? 'standard',
                        'price_per_night' => (float) $bookedRoom->room->price_per_night,
                        'cleaning_fee' => (float) ($bookedRoom->room->cleaning_fee ?? 0),
                        'service_fee' => (float) ($bookedRoom->room->service_fee ?? 0),
                        'beds_count' => $bookedRoom->room->beds_count,
                        'bathrooms_count' => $bookedRoom->room->bathrooms_count,
                        'rooms_count' => $bookedRoom->room->rooms_count,
                        'images' => $roomImages,
                    ];
                });

                return [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'hotel' => $booking->hotel ? [
                        'id' => $booking->hotel->id,
                        'name-ar' =>$booking->hotel->name_ar,
                        'name_en' => $booking->hotel->name_en,
                        'address_ar' => $booking->hotel->address_ar,
                        'address_en' =>$booking->hotel->address_en,
                        'phone' => $booking->hotel->phone,
                        'phone_2' => $booking->hotel->phone_2,
                        'website_url' => $booking->hotel->website_url,
                        'rate' => $booking->hotel->rate,
                        'type' => $booking->hotel->type,
                        'services' => $booking->hotel->services,
                        'images' => $hotelImages,
                        'province' => $booking->hotel->province ? [
                            'id' => $booking->hotel->province->id,
                            'name' => app()->getLocale() === 'ar' ? $booking->hotel->province->name_ar : $booking->hotel->province->name_en,
                        ] : null,
                        'owner' => $booking->hotel->user ? [
                            'id' => $booking->hotel->user->id,
                            'name' => $booking->hotel->user->name,
                            'phone' => $booking->hotel->user->phone,
                            'image' => $booking->hotel->user->image,
                        ] : null,
                    ] : null,
                    'rooms' => $rooms,
                    'check_in_date' => $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : null,
                    'check_out_date' => $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : null,
                    'nights_count' => $booking->nights_count,
                    'guests_count' => $booking->guests_count,
                    'adults_count' => $booking->adults_count,
                    'young_count' => $booking->young_count,
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
         * Get user event bookings.
         */
        public function getUserEventBookings(Request $request): JsonResponse
        {
            $query = EventTicket::with(['event', 'event.user'])
                ->where('user_id', Auth::id());

            // Filter by status
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            $eventBookings = $query->orderByDesc('created_at')
                ->get()
                ->map(function ($ticket) {
                    return [
                        'id' => $ticket->id,
                        'ticket_reference' => $ticket->ticket_reference,
                        'tickets_count' => $ticket->tickets_count,
                        'total_price' => (float) $ticket->total_price,
                        'status' => $ticket->status,
                        'notes' => $ticket->notes,
                        'event' => $ticket->event ? [
                            'id' => $ticket->event->id,
                            'name_ar' => $ticket->event->name_ar,
                            'name_en' => $ticket->event->name_en,
                            'location_ar' => $ticket->event->location_ar,
                            'location_en' => $ticket->event->location_en,
                            'location_url' => $ticket->event->location_url,
                            'event_date' => $ticket->event->event_date ? $ticket->event->event_date->format('Y-m-d H:i:s') : null,
                            'price_per_person' => (float) $ticket->event->price_per_person,
                            'duration' => $ticket->event->duration,
                            'phone' => $ticket->event->phone,
                            'activity_type' => $ticket->event->activity_type,
                            'activity_images' => $ticket->event->activity_images,
                            'owner' => $ticket->event->user ? [
                                'id' => $ticket->event->user->id,
                                'name' => $ticket->event->user->name,
                                'phone' => $ticket->event->user->phone,
                                'image' => $ticket->event->user->image,
                            ] : null,
                        ] : null,
                        'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $eventBookings,
            ]);
        }
    public function getBookingDetails(Booking $booking): JsonResponse
    {
        if ($booking->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => __('api.bookings.unauthorized'),
            ], 403);
        }

        $booking->load([
            'hotel', 'hotel.province', 'hotel.media', 'hotel.user',
            'bookedRooms.room', 'bookedRooms.room.media', 'payments'
        ]);

        $hotelImages = $booking->hotel && $booking->hotel->media ? $booking->hotel->media->where('type', 'image')->map(function ($media) {
            return [
                'url' => $media->file_url,
                'order' => $media->order_column,
            ];
        })->sortBy('order')->values() : [];

        $rooms = $booking->bookedRooms->map(function ($bookedRoom) {
            $roomImages = $bookedRoom->room && $bookedRoom->room->media ? 
                $bookedRoom->room->media->where('type', 'image')->map(function ($media) {
                    return [
                        'url' => $media->file_url,
                        'order' => $media->order_column,
                    ];
                })->sortBy('order')->values() : [];

            return [
                'id' => $bookedRoom->room->id,
                'name' => $bookedRoom->room->name ?? 'Room ' . $bookedRoom->room->id,
                'type' => $bookedRoom->room->type ?? 'standard',
                'price_per_night' => (float) $bookedRoom->room->price_per_night,
                'cleaning_fee' => (float) ($bookedRoom->room->cleaning_fee ?? 0),
                'service_fee' => (float) ($bookedRoom->room->service_fee ?? 0),
                'beds_count' => $bookedRoom->room->beds_count,
                'bathrooms_count' => $bookedRoom->room->bathrooms_count,
                'rooms_count' => $bookedRoom->room->rooms_count,
                'images' => $roomImages,
            ];
        });

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
                    'name_ar' => $booking->hotel->name_ar,
                    'name_en' => $booking->hotel->name_en,
                    'address_ar' => $booking->hotel->address_ar,
                    'address_en' => $booking->hotel->address_en,
                    'phone' => $booking->hotel->phone,
                    'phone_2' => $booking->hotel->phone_2,
                    'website_url' => $booking->hotel->website_url,
                    'rate' => $booking->hotel->rate,
                    'type' => $booking->hotel->type,
                    'services' => $booking->hotel->services,
                    'images' => $hotelImages,
                    'province' => $booking->hotel->province ? [
                        'id' => $booking->hotel->province->id,
                        'name_ar' => $booking->hotel->province->name_ar,
                        'name_en' => $booking->hotel->province->name_en,
                    ] : null,
                    'owner' => $booking->hotel->user ? [
                        'id' => $booking->hotel->user->id,
                        'name' => $booking->hotel->user->name,
                        'phone' => $booking->hotel->user->phone,
                        'image' => $booking->hotel->user->image,
                    ] : null,
                ] : null,
                'rooms' => $rooms,
                'check_in_date' => $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : null,
                'check_out_date' => $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : null,
                'nights_count' => $booking->nights_count,
                'guests_count' => $booking->guests_count,
                'adults_count' => $booking->adults_count,
                'young_count' => $booking->young_count,
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
                'room_ids' => 'required|array|min:1',
                'room_ids.*' => 'required|exists:hotel_rooms,id',
                'check_in_date' => 'required|date|after_or_equal:today',
                'check_out_date' => 'required|date|after:check_in_date',
                'adults_count' => 'required|integer|min:1|max:100',
                'young_count' => 'required|integer|min:0|max:100',
                'voucher_code' => 'nullable|string|exists:vouchers,code',
                'notes' => 'nullable|string|max:1000',
            ]);

            $validated['guests_count'] = $validated['adults_count'] + $validated['young_count'];

            $hotel = Hotel::findOrFail($validated['hotel_id']);

            if (!$hotel->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.hotels.hotel_not_available'),
                ], 400);
            }

            // Validate all rooms belong to hotel and are active
            $rooms = HotelRoom::whereIn('id', $validated['room_ids'])
                ->where('hotel_id', $hotel->id)
                ->where('is_active', true)
                ->get();

            if ($rooms->count() !== count($validated['room_ids'])) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.hotels.room_not_available'),
                ], 400);
            }

            // Validate capacity
            $totalMaxPeople = $rooms->sum('max_people');
            if ($validated['guests_count'] > $totalMaxPeople) {
                return response()->json([
                    'success' => false,
                    'message' => 'عدد الأشخاص يتجاوز السعة القصوى للغرف المختارة (' . $totalMaxPeople . ' شخص)',
                ], 400);
            }

            // Check availability for each room
            foreach ($rooms as $room) {
                // Check via BookingRoom pivot table
                $conflict = BookingRoom::where('room_id', $room->id)
                    ->whereHas('booking', function($q) use ($validated) {
                        $q->where(function ($query) use ($validated) {
                            $query->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                                ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                                ->orWhere(function ($q) use ($validated) {
                                    $q->where('check_in_date', '<=', $validated['check_in_date'])
                                        ->where('check_out_date', '>=', $validated['check_out_date']);
                                });
                        })->whereIn('status', ['pending', 'confirmed', 'checked_in']);
                    })
                    ->exists();

                // Also check for iCal-imported bookings (direct room bookings)
                if (!$conflict) {
                    $conflict = Booking::where('room_id', $room->id)
                        ->whereNotNull('ical_url_id') // Only check iCal imports
                        ->where(function ($query) use ($validated) {
                            $query->whereBetween('check_in_date', [$validated['check_in_date'], $validated['check_out_date']])
                                ->orWhereBetween('check_out_date', [$validated['check_in_date'], $validated['check_out_date']])
                                ->orWhere(function ($q) use ($validated) {
                                    $q->where('check_in_date', '<=', $validated['check_in_date'])
                                        ->where('check_out_date', '>=', $validated['check_out_date']);
                                });
                        })
                        ->whereIn('status', ['pending', 'confirmed', 'checked_in'])
                        ->exists();
                }

                if ($conflict) {
                    return response()->json([
                        'success' => false,
                        'message' => __('api.bookings.room_not_available_dates') . ' (Room ID: ' . $room->id . ')',
                    ], 400);
                }
            }

            // Calculate total price
            $nights = max(1, \Carbon\Carbon::parse($validated['check_in_date'])->diffInDays($validated['check_out_date']));
            $totalPrice = $rooms->sum('price_per_night') * $nights;
            
            // Handle voucher
            $voucher = null;
            $discountAmount = 0;
            $finalPrice = $totalPrice;
            
            if (!empty($validated['voucher_code'])) {
                $voucher = \App\Models\Voucher::where('code', $validated['voucher_code'])->first();
                
                if (!$voucher || !$voucher->isValid()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'كود الخصم غير صالح',
                    ], 400);
                }
                
                if (\App\Models\UserVoucher::where('user_id', Auth::id())->where('voucher_id', $voucher->id)->exists()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'تم استخدام هذا الكود من قبل',
                    ], 400);
                }
                
                $discountAmount = $voucher->calculateDiscount($totalPrice);
                $finalPrice = $totalPrice - $discountAmount;
            }

            // Create booking
            $booking = Booking::create([
                'user_id' => Auth::id(),
                'hotel_id' => $hotel->id,
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'guests_count' => $validated['guests_count'],
                'adults_count' => $validated['adults_count'],
                'young_count' => $validated['young_count'],
                'rooms_count' => count($validated['room_ids']),
                'price_per_night' => $rooms->sum('price_per_night'),
                'total_price' => $finalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);
            
            // Link rooms to booking
            foreach ($validated['room_ids'] as $roomId) {
                BookingRoom::create([
                    'booking_id' => $booking->id,
                    'room_id' => $roomId,
                ]);
            }
            
            // Record voucher usage
            if ($voucher && $discountAmount > 0) {
                \App\Models\UserVoucher::create([
                    'user_id' => Auth::id(),
                    'voucher_id' => $voucher->id,
                    'booking_id' => $booking->id,
                    'discount_amount' => $discountAmount,
                    'used_at' => now(),
                ]);
                
                $voucher->increment('used_count');
            }

            // Notify Admins
            app(\App\Services\FirebaseNotificationService::class)->sendToAdmins(
                "حجز جديد",
                "قام المستخدم ({$booking->user->name}) بإجراء حجز جديد برقم مرجع ({$booking->booking_reference}).",
                "new_booking",
                ['booking_id' => $booking->id, 'reference' => $booking->booking_reference]
            );

            return response()->json([
                'success' => true,
                'message' => __('api.bookings.created'),
                'data' => [
                    'id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'original_price' => $totalPrice,
                    'discount_amount' => $discountAmount,
                    'final_price' => $finalPrice,
                    'nights' => $nights,
                    'rooms_booked' => count($validated['room_ids']),
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

