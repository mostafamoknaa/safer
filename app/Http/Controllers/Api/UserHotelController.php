<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\HotelMedia;
use App\Models\HotelRoom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserHotelController extends Controller
{
    /**
     * Get user's hotels.
     */
    public function getUserHotels(): JsonResponse
    {
        $hotels = Hotel::where('user_id', Auth::id())
            ->with(['media', 'province', 'icalUrls'])
            ->get()
            ->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'name_ar' => $hotel->name_ar,
                    'name_en' => $hotel->name_en,
                    'type' => $hotel->type,
                    'price' => (float) $hotel->price,
                    'address_ar' => $hotel->address_ar,
                    'address_en' => $hotel->address_en,
                    'phone' => $hotel->phone,
                    'phone_2' => $hotel->phone_2,
                    'description_ar' => $hotel->description_ar,
                    'description_en' => $hotel->description_en,
                    'cancellation_policy' => $hotel->cancellation_policy,
                    'lat' => $hotel->lat,
                    'lang' => $hotel->lang,
                    'country' => $hotel->country,
                    'website_url' => $hotel->website_url,
                    'province' => $hotel->province,
                    'services' => $hotel->services,
                    'is_active' => $hotel->is_active,
                    'schedule_type' => $hotel->schedule_type,
                    'daily_price' => $hotel->hourly_price ? (float) $hotel->hourly_price : null,
                    'booking_settings' => $hotel->booking_settings,
                    'week_schedule' => $hotel->week_schedule,
                    'blocked_dates' => $hotel->blocked_dates,
                    'identity_images' => $hotel->identity_images,
                    'lease_agreement' => $hotel->lease_agreement,
                    'ical' => $hotel->icalUrls,
                    'images' => $hotel->media->map(fn($media) => asset('storage/' . $media->file_path)),
                    'created_at' => $hotel->created_at,
                    'updated_at' => $hotel->updated_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $hotels,
        ]);
    }

    /**
     * Store a new hotel.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name_ar' => 'nullable|string|max:255',
            'name_en' => 'nullable|string|max:255',
            'price' => 'nullable|numeric|min:0',
            'address_ar' => 'nullable|string|max:500',
            'address_en' => 'nullable|string|max:500',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'description_ar' => 'nullable|string|max:2000',
            'description_en' => 'nullable|string',
            'cancellation_policy' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_card_front' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_card_back' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'lease_agreement' => 'required|array|max:5',
            'lease_agreement.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'lat' => 'required|numeric',
            'lang' => 'required|numeric',
            'country' => 'nullable|string|max:255',
            'website_url' => 'nullable|string|max:255',
            'icals' => 'nullable|array',
            'icals.*.name' => 'nullable|string|max:100',
            'icals.*.url' => 'required|url|max:500',
        ]);

        // Upload documents
        $identityImages = [];
        if ($request->hasFile('id_card_front')) {
            $identityImages['front'] = $request->file('id_card_front')->store('hotels/documents', 'public');
        }
        if ($request->hasFile('id_card_back')) {
            $identityImages['back'] = $request->file('id_card_back')->store('hotels/documents', 'public');
        }

        $validated['province_id'] = 1; // Default province ID
        
        $validated['identity_images'] = $identityImages;
      
        $leaseAgreements = [];
        if ($request->hasFile('lease_agreement')) {
            foreach ($request->file('lease_agreement') as $index => $file) {
                $leaseAgreements[] = $file->store('hotels/documents', 'public');
            }
        }
        $validated['lease_agreement'] = $leaseAgreements;

        // Process services
        if ($request->has('services')) {
            $services = \App\Models\Service::whereIn('id', $request->services)->get();
            $validated['services'] = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name_ar' => $service->name_ar,
                    'name_en' => $service->name_en,
                    'image' => $service->image,
                ];
            })->toArray();
        }

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = false; // Pending approval

        $hotel = Hotel::create($validated);

        // Notify
        app(\App\Services\FirebaseNotificationService::class)->notifySubmission($hotel->user, $hotel->name_ar ?: $hotel->name_en, 'hotel');

        // Handle hotel images only
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('hotels/' . $hotel->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => $index,
                ]);
            }
        }

        // Handle iCal URLs
        if ($request->has('icals')) {
            foreach ($request->icals as $ical) {
                $hotel->icalUrls()->create($ical);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب إضافة الفندق بنجاح وسيتم مراجعته',
            'data' => ['hotel_id' => $hotel->id],
        ], 201);
    }

    /**
     * Update hotel.
     */
    public function update(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا الفندق',
            ], 403);
        }

        $validated = $request->validate([
            'type' => 'required|string|max:255',
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'address_ar' => 'required|string|max:500',
            'address_en' => 'required|string|max:500',
            'phone' => 'required|string|max:20',
            'phone_2' => 'nullable|string|max:20',
            'description_ar' => 'required|string|max:2000',
            'description_en' => 'required|string',
            'cancellation_policy' => 'nullable|array',
            'schedule_type' => 'required|in:hourly,daily',
            'hourly_price' => 'nullable|numeric|min:0',
            'booking_settings' => 'nullable|array',
            'booking_settings.min_hours' => 'nullable|integer|min:1',
            'booking_settings.max_hours' => 'nullable|integer|min:1',
            'booking_settings.advance_booking_days' => 'nullable|integer|min:0',
            'week_schedule' => 'nullable|array',
            'week_schedule.*.day' => 'required|string|in:saturday,sunday,monday,tuesday,wednesday,thursday,friday',
            'week_schedule.*.is_available' => 'required|boolean',
            'week_schedule.*.time_slots' => 'nullable|array',
            'week_schedule.*.time_slots.*.from' => 'nullable|date_format:H:i',
            'week_schedule.*.time_slots.*.to' => 'nullable|date_format:H:i',
            'blocked_dates' => 'nullable|array',
            'blocked_dates.*.date' => 'required|date_format:Y-m-d',
            'blocked_dates.*.reason' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
            'lat' => 'nullable|numeric',
            'lang' => 'nullable|numeric',
            'country' => 'nullable|string|max:255',
            'website_url' => 'nullable|string|max:255',
            'icals' => 'nullable|array',
            'icals.*.id' => 'nullable|exists:ical_urls,id',
            'icals.*.name' => 'nullable|string|max:100',
            'icals.*.url' => 'required|url|max:500',
        ]);

        if ($request->has('services')) {
            $services = \App\Models\Service::whereIn('id', $request->services)->get();
            $validated['services'] = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name_ar' => $service->name_ar,
                    'name_en' => $service->name_en,
                    'image' => $service->image,
                ];
            })->toArray();
        }

        $hotel->update($validated);

        // Add new images
        if ($request->hasFile('images')) {
            $orderColumn = $hotel->media()->max('order_column') ?? -1;
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/' . $hotel->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => ++$orderColumn,
                ]);
            }
        }

        // Handle iCal URLs update
        if ($request->has('icals')) {
            foreach ($request->icals as $ical) {
                if (isset($ical['id'])) {
                    $hotel->icalUrls()->where('id', $ical['id'])->update([
                        'name' => $ical['name'] ?? null,
                        'url' => $ical['url'],
                    ]);
                } else {
                    $hotel->icalUrls()->create($ical);
                }
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث الفندق بنجاح',
        ]);
    }

    /**
     * Get hotel details.
     */
    public function show(Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بعرض هذا الفندق',
            ], 403);
        }

        $hotel->load(['media', 'rooms.media', 'bookings.user', 'bookings.room', 'icalUrls']);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $hotel->id,
                'type' => $hotel->type,
                'name_ar' => $hotel->name_ar,
                'name_en' => $hotel->name_en,
                'price' => (float) $hotel->price,
                'address_ar' => $hotel->address_ar,
                'address_en' => $hotel->address_en,
                'cancellation_policy' => $hotel->cancellation_policy,
                'phone' => $hotel->phone,
                'phone_2' => $hotel->phone_2,
                'description_ar' => $hotel->description_ar,
                'description_en' => $hotel->description_en,
                'is_active' => $hotel->is_active,
                'lat' => $hotel->lat,
                'lang' => $hotel->lang,
                'country' => $hotel->country,
                'website_url' => $hotel->website_url,
                'province' => $hotel->province,
                'services' => $hotel->services,
                'schedule_type' => $hotel->schedule_type,
                'hourly_price' => $hotel->hourly_price ? (float) $hotel->hourly_price : null,
                'booking_settings' => $hotel->booking_settings,
                'week_schedule' => $hotel->week_schedule,
                'blocked_dates' => $hotel->blocked_dates,
                'identity_images' => $hotel->identity_images,
                'lease_agreement' => $hotel->lease_agreement,
                'ical_urls' => $hotel->icalUrls,
                'images' => $hotel->media->map(function($media) {
                    return [
                        'id' => $media->id,
                        'url' => asset('storage/' . $media->file_path),
                    ];
                }),
                'rooms' => $hotel->rooms->map(function($room) {
                    return [
                        'id' => $room->id,
                        'name' => $room->name,
                        'price_per_night' => (float) $room->price_per_night,
                        'beds_count' => $room->beds_count,
                        'bathrooms_count' => $room->bathrooms_count,
                        'rooms_count' => $room->rooms_count,
                        'cleaning_fee' => $room->cleaning_fee,
                        'service_fee' => $room->service_fee,
                        'is_active' => $room->is_active,
                        'services' => $room->services,
                        'ical_urls' => $room->icalUrls,
                        'images' => $room->media->map(fn($m) => [
                            'id' => $m->id,
                            'url' => asset('storage/' . $m->file_path),
                        ]),
                    ];
                }),

                'bookings' => $hotel->bookings->map(function($booking) {
                    return [
                        'id' => $booking->id,
                        'user' => [
                            'id' => $booking->user->id,
                            'name' => $booking->user->name,
                            'email' => $booking->user->email,
                        ],
                        'room' => $booking->room ? [
                            'id' => $booking->room->id,
                            'name' => $booking->room->name,
                        ] : null,
                        'check_in' => $booking->check_in,
                        'check_out' => $booking->check_out,
                        'total_price' => (float) $booking->total_price,
                        'status' => $booking->status,
                        'created_at' => $booking->created_at,
                    ];
                }),
                'created_at' => $hotel->created_at,
                'updated_at' => $hotel->updated_at,
            ],
        ]);
    }

    /**
     * Delete hotel.
     */
    public function destroy(Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بحذف هذا الفندق',
            ], 403);
        }

        foreach ($hotel->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        foreach ($hotel->rooms as $room) {
            foreach ($room->media as $media) {
                Storage::disk('public')->delete($media->file_path);
            }
        }

        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الفندق بنجاح',
        ]);
    }

    /**
     * Clone a hotel.
     */
    public function clone(Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Clone Hotel
        $newHotel = $hotel->replicate();
        $newHotel->name_en .= ' (Copy)';
        $newHotel->name_ar .= ' (نسخة)';
        $newHotel->is_active = false; // Reset status
        $newHotel->push();

        // Notify
        app(\App\Services\FirebaseNotificationService::class)->notifySubmission($newHotel->user, $newHotel->name_ar ?: $newHotel->name_en, 'hotel');

        // Clone Hotel Media
        foreach ($hotel->media as $media) {
            $newMedia = $media->replicate();
            $newMedia->hotel_id = $newHotel->id;
            $newMedia->push();
        }

        // Clone Rooms and Room Media
        foreach ($hotel->rooms as $room) {
            $newRoom = $room->replicate();
            $newRoom->hotel_id = $newHotel->id;
            $newRoom->push();

            foreach ($room->media as $roomMedia) {
                $newRoomMedia = $roomMedia->replicate();
                $newRoomMedia->hotel_id = $newHotel->id;
                $newRoomMedia->room_id = $newRoom->id;
                $newRoomMedia->push();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Hotel cloned successfully pending approval',
            'data' => ['hotel_id' => $newHotel->id],
        ], 201);
    }

    /**
     * Get all hotel bookings.
     */
    public function getBookings(Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $bookings = Booking::where('hotel_id', $hotel->id)
            ->with(['bookedRooms.room.media', 'user'])
            ->latest()
            ->get()
            ->map(function($booking) {
                return [
                    'id' => $booking->id,
                    'user' => $booking->user,
                    'check_in_date' => $booking->check_in_date,
                    'check_out_date' => $booking->check_out_date,
                    'guests_count' => $booking->guests_count,
                    'rooms_count' => $booking->rooms_count,
                    'total_price' => (float) $booking->total_price,
                    'status' => $booking->status,
                    'booking_reference' => $booking->booking_reference,
                    'rooms' => $booking->bookedRooms->map(fn($br) => [
                        'id' => $br->room->id,
                        'name' => $br->room->name,
                        'price_per_night' => (float) $br->room->price_per_night,
                        'images' => $br->room->media->map(fn($m) => asset('storage/' . $m->file_path)),
                    ]),
                    'created_at' => $booking->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $bookings,
        ]);
    }

    /**
     * Get all hotel rooms.
     */
    public function getRooms(Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $rooms = HotelRoom::where('hotel_id', $hotel->id)
            ->with('media')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $rooms,
        ]);
    }
}