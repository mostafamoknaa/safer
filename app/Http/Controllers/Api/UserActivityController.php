<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserActivityController extends Controller
{
    /**
     * Store a new activity.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:2000',
            'name_en' => 'required|string|max:2000',
            'type' => 'required|in:activity',
            'location_url' => 'nullable',
            'lat'  => 'nullable|numeric',
            'lng'  => 'nullable|numeric',
            'activity_type' => 'required|in:رياضة,سفر,تسوق,عمل,تعليم,ترفيه,صحة,اجتماعات',
            'price_per_person' => 'required|numeric|min:0',
            'event_date' => 'required|date',
            'available_tickets' => 'required|integer|min:1',
            'duration' => 'required|string|max:255',
            'location_ar' => 'required|string|max:500',
            'location_en' => 'required|string|max:500',
            'description_ar' => 'required|string|max:2000',
            'description_en' => 'required|string|max:2000',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'cancellation_policy' => 'nullable|array',
            'activity_images' => 'required|array|max:5',
            'activity_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_images' => 'required|array|size:2',
            'id_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        // Upload activity images
        $activityImages = [];
        if ($request->hasFile('activity_images')) {
            foreach ($request->file('activity_images') as $image) {
                $activityImages[] = $image->store('activities/images', 'public');
            }
        }

        // Upload ID images
        $idImages = [];
        if ($request->hasFile('id_images')) {
            foreach ($request->file('id_images') as $index => $image) {
                $type = $index === 0 ? 'front' : 'back';
                $idImages[$type] = $image->store('activities/documents', 'public');
            }
        }

        $activity = Event::create([
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'type' => $validated['type'],
    
            'event_date' => $validated['event_date'],
    
            'location_ar' => $validated['location_ar'],
            'location_en' => $validated['location_en'],
            'location_url' => $validated['location_url'] ?? null,
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
    
            'description_ar' => $validated['description_ar'],
            'description_en' => $validated['description_en'],
    
            'activity_type' => $validated['activity_type'],
            'price_per_person' => $validated['price_per_person'],
            'price' => $validated['price_per_person'],
    
            'available_tickets' => $validated['available_tickets'],
            'duration' => $validated['duration'],
    
            'phone' => $validated['phone'],
            'phone_secondary' => $validated['phone_secondary'] ?? null,
    
            'activity_images' => $activityImages,
            'id_images' => $idImages,
            'cancellation_policy' => $validated['cancellation_policy'] ?? null,
    
            'user_id' => Auth::id(),
            'is_active' => false,
        ]);
    

        return response()->json([
            'success' => true,
            'message' => 'تم إرسال طلب إضافة النشاط بنجاح وسيتم مراجعته',
            'data' => ['activity_id' => $activity->id],
        ], 201);
    }

    /**
     * Update activity.
     */
    public function update(Request $request, Event $activity): JsonResponse
    {
        if ($activity->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'غير مصرح لك بتعديل هذا النشاط',
            ], 403);
        }

        $validated = $request->validate([
            'name_ar' => 'nullable|string|max:2000',
            'name_en' => 'nullable|string|max:2000',
    
            'type' => 'nullable|in:activity',
            'activity_type' => 'nullable|in:رياضة,سفر,تسوق,عمل,تعليم,ترفيه,صحة,اجتماعات',
    
            'price_per_person' => 'nullable|numeric|min:0',
            'duration' => 'nullable|string|max:255',
    
            'available_tickets' => 'nullable|integer|min:1',
    
            'location_ar' => 'nullable|string|max:500',
            'location_en' => 'nullable|string|max:500',
    
            'description_ar' => 'nullable|string|max:2000',
            'description_en' => 'nullable|string|max:2000',
    
            'phone' => 'nullable|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',

            'event_date' => 'nullable|date',
    
    
            'activity_images' => 'nullable|array|max:5',
            'activity_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);
    
        $updateData = [
            'name_ar' => $validated['name_ar'],
            'name_en' => $validated['name_en'],
            'type' => $validated['type'],
    
            'location_ar' => $validated['location_ar'],
            'location_en' => $validated['location_en'],
    
            'description_ar' => $validated['description_ar'],
            'description_en' => $validated['descriptio_en'],
    
            'activity_type' => $validated['activity_type'],
            'price_per_person' => $validated['price_per_person'],
            'price' => $validated['price_per_person'],
    
            'available_tickets' => $validated['available_tickets'],
            'duration' => $validated['duration'],
    
            'phone' => $validated['phone'],
            'phone_secondary' => $validated['phone_secondary'] ?? null,

            'event_date' => $validated['event_date'],

        ];

        // Upload new activity images if provided
        if ($request->hasFile('activity_images')) {
            $activityImages = [];
            foreach ($request->file('activity_images') as $image) {
                $activityImages[] = $image->store('activities/images', 'public');
            }
            $updateData['activity_images'] = $activityImages;
        }


        $activity->update($updateData);

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث النشاط بنجاح',
        ]);
    }

    /**
     * Get user's activities.
     */
    public function getUserActivities(): JsonResponse
    {
        $activities = Event::where('user_id', Auth::id())
            ->whereNotNull('activity_type')
            ->get()
            ->map(function ($activity) {
                return [
                    'id' => $activity->id,
                    'activity_type' => $activity->activity_type,
                    'price_per_person' => (float) $activity->price_per_person,
                    'duration' => $activity->duration,
                    'location' => $activity->location_ar,
                    'description' => $activity->description_ar,
                    'phone' => $activity->phone,
                    'phone_secondary' => $activity->phone_secondary,
                    'max_participants' => $activity->max_participants,
                    'instant_booking' => $activity->instant_booking,
                    'allow_cancellation' => $activity->allow_cancellation,
                    'cancellation_hours' => $activity->cancellation_hours,
                    'is_active' => $activity->is_active,
                    'activity_images' => collect($activity->activity_images)->map(fn($img) => asset('storage/' . $img)),
                    'cancellation_policy' => $activity->cancellation_policy,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $activities,
        ]);
    }


}