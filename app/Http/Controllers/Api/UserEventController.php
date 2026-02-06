<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserEventController extends Controller
{
    /**
     * Get user's events.
     */
    public function index(): JsonResponse
    {
        $events = Event::where('user_id', Auth::id())
            ->orderBy('event_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * Store a new event.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'cancellation_policy' => 'nullable|array',
            'activity_images' => 'nullable|array|max:5',
            'activity_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        // Handle images upload
        if ($request->hasFile('activity_images')) {
            $images = [];
            foreach ($request->file('activity_images') as $image) {
                $images[] = $image->store('events/images', 'public');
            }
            $validated['activity_images'] = $images;
        }

        $event = Event::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event added successfully',
            'data' => $event,
        ], 201);
    }

    /**
     * Update an event.
     */
    public function update(Request $request, Event $event): JsonResponse
    {
        if ($event->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name_ar' => 'string|max:255',
            'name_en' => 'string|max:255',
            'location_ar' => 'string|max:255',
            'location_en' => 'string|max:255',
            'event_date' => 'date',
            'price' => 'numeric|min:0',
            'available_tickets' => 'integer|min:1',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'cancellation_policy' => 'nullable|array',
            'is_active' => 'boolean',
            'activity_images' => 'nullable|array|max:5',
            'activity_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        if ($request->hasFile('activity_images')) {
            $currentImages = $event->activity_images ?? [];
            foreach ($request->file('activity_images') as $image) {
                $currentImages[] = $image->store('events/images', 'public');
            }
            $validated['activity_images'] = $currentImages;
        }

        $event->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Event updated successfully',
            'data' => $event,
        ]);
    }

    /**
     * Delete an event.
     */
    public function destroy(Event $event): JsonResponse
    {
        if ($event->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Logic to prevent deleting if tickets are sold could be added here
        if ($event->tickets()->exists()) {
             return response()->json(['success' => false, 'message' => 'Cannot delete event with purchased tickets'], 400);
        }

        $event->delete();

        return response()->json([
            'success' => true,
            'message' => 'Event deleted successfully',
        ]);
    }

    /**
     * Clone an event.
     */
    public function clone(Event $event): JsonResponse
    {
        if ($event->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newEvent = $event->replicate();
        $newEvent->name_en .= ' (Copy)';
        $newEvent->name_ar .= ' (نسخة)';
        $newEvent->tickets_count = 0; // Reset calculated fields if any, though accessor logic is usually safe
        $newEvent->push();

        return response()->json([
            'success' => true,
            'message' => 'Event cloned successfully',
            'data' => $newEvent,
        ], 201);
    }
}
