<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelMedia;
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
            ->with(['media', 'province'])
            ->get()
            ->map(function ($hotel) {
                return [
                    'id' => $hotel->id,
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'type' => $hotel->type,
                    'price' => (float) $hotel->price,
                    'location' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'phone' => $hotel->phone,
                    'phone_2' => $hotel->phone_2,
                    'description' => app()->getLocale() === 'ar' ? $hotel->description_ar : $hotel->description_en,
                    'is_active' => $hotel->is_active,
                    'schedule_type' => $hotel->schedule_type,
                    'hourly_price' => $hotel->hourly_price ? (float) $hotel->hourly_price : null,
                    'booking_settings' => $hotel->booking_settings,
                    'week_schedule' => $hotel->week_schedule,
                    'blocked_dates' => $hotel->blocked_dates,
                    'is_active' => $hotel->is_active,
                    'images' => $hotel->media->map(fn($media) => asset('storage/' . $media->file_path)),
                    'images' => $hotel->media->map(fn($media) => asset('storage/' . $media->file_path)),
                    
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
            'description_en' => 'nullable|string|max:2000',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_card_front' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_card_back' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'lease_agreement' => 'required|array|max:5',
            'lease_agreement.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
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

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = false; // Pending approval

        $hotel = Hotel::create($validated);

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
            'description_en' => 'required|string|max:2000',
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
            'blocked_dates.*.date' => 'required|date',
            'blocked_dates.*.reason' => 'nullable|string|max:255',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

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
                'phone' => $hotel->phone,
                'phone_2' => $hotel->phone_2,
                'description_ar' => $hotel->description_ar,
                'description_en' => $hotel->description_en,
                'is_active' => $hotel->is_active,
                'images' => $hotel->media->map(function($media) {
                    return [
                        'id' => $media->id,
                        'url' => asset('storage/' . $media->file_path),
                    ];
                }),
            ],
        ]);
    }
}