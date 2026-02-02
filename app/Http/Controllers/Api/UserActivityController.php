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
            'type' => 'required|in:activity',
            'activity_type' => 'required|in:رياضة,سفر,تسوق,عمل,تعليم,ترفيه,صحة,اجتماعات',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'location' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
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
            'name_ar' => $validated['description'],
            'name_en' => $validated['description'],
            'event_date' => now(),
            'location_ar' => $validated['location'],
            'location_en' => $validated['location'],
            'description_ar' => $validated['description'],
            'description_en' => $validated['description'],
            'activity_type' => $validated['activity_type'],
            'price_per_person' => $validated['price_per_person'],
            'price' =>  $validated['price_per_person'],
            'duration' => $validated['duration'],
            'phone' => $validated['phone'],
            'phone_secondary' => $validated['phone_secondary'],
            'activity_images' => $activityImages,
            'id_images' => $idImages,
            'user_id' => Auth::id(),
            'is_active' => false, // Pending approval
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
            'type' => 'required|in:activity',
            'activity_type' => 'required|in:رياضة,سفر,تسوق,عمل,تعليم,ترفيه,صحة,اجتماعات',
            'price_per_person' => 'required|numeric|min:0',
            'duration' => 'required|string|max:255',
            'max_participants' => 'required|integer|min:1',
            'location' => 'required|string|max:500',
            'description' => 'required|string|max:2000',
            'phone' => 'required|string|max:20',
            'phone_secondary' => 'nullable|string|max:20',
            'instant_booking' => 'required|boolean',
            'allow_cancellation' => 'required|boolean',
            'cancellation_hours' => 'nullable|integer|min:1',
            'activity_images' => 'nullable|array|max:5',
            'activity_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'id_images' => 'nullable|array|size:2',
            'id_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $updateData = [
            'name_ar' => $validated['description'],
            'name_en' => $validated['description'],
            'location_ar' => $validated['location'],
            'location_en' => $validated['location'],
            'description_ar' => $validated['description'],
            'description_en' => $validated['description'],
            'activity_type' => $validated['activity_type'],
            'price_per_person' => $validated['price_per_person'],
            'duration' => $validated['duration'],
            'max_participants' => $validated['max_participants'],
            'phone' => $validated['phone'],
            'phone_secondary' => $validated['phone_secondary'],
            'instant_booking' => $validated['instant_booking'],
            'allow_cancellation' => $validated['allow_cancellation'],
            'cancellation_hours' => $validated['cancellation_hours'],
        ];

        // Upload new activity images if provided
        if ($request->hasFile('activity_images')) {
            $activityImages = [];
            foreach ($request->file('activity_images') as $image) {
                $activityImages[] = $image->store('activities/images', 'public');
            }
            $updateData['activity_images'] = $activityImages;
        }

        // Upload new ID images if provided
        if ($request->hasFile('id_images')) {
            $idImages = [];
            foreach ($request->file('id_images') as $index => $image) {
                $type = $index === 0 ? 'front' : 'back';
                $idImages[$type] = $image->store('activities/documents', 'public');
            }
            $updateData['id_images'] = $idImages;
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