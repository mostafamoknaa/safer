<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelRoom;
use App\Models\HotelMedia;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserHotelRoomController extends Controller
{
    /**
     * Store a new room.
     */
    public function store(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'price_per_night' => 'required|numeric|min:0',
            'beds_count' => 'required|integer|min:1',
            'bathrooms_count' => 'required|integer|min:1',
            'rooms_count' => 'required|integer|min:1',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'cleaning_fee' => 'nullable',
            'service_fee' => 'nullable',
            'name' => 'required|string',
        ]);

        $validated['hotel_id'] = $hotel->id;
        $validated['is_active'] = true;

        $validated['is_active'] = true;

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

        $room = HotelRoom::create($validated);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('hotels/' . $hotel->id . '/rooms/' . $room->id, 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id, // Associated with hotel
                    'room_id' => $room->id,   // And specifically this room
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => $index,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Room added successfully',
            'data' => $room->load('media'),
        ], 201);
    }

    /**
     * Update a room.
     */
    public function update(Request $request, HotelRoom $room): JsonResponse
    {
        if ($room->hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'price_per_night' => 'numeric|min:0',
            'beds_count' => 'integer|min:1',
            'bathrooms_count' => 'integer|min:1',
            'rooms_count' => 'integer|min:1',
            'is_active' => 'boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'cleaning_fee' => 'nullable',
            'service_fee' => 'nullable',
            'name' => 'required|string',
        ]);

        $room->update($validated);

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

        $room->update($validated);

        if ($request->hasFile('images')) {
            // Append new images
            $existingCount = HotelMedia::where('room_id', $room->id)->count();
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('hotels/' . $room->hotel_id . '/rooms/' . $room->id, 'public');
                HotelMedia::create([
                    'hotel_id' => $room->hotel_id,
                    'room_id' => $room->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => $existingCount + $index,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Room updated successfully',
            'data' => $room->load('media'),
        ]);
    }

    /**
     * Delete a room.
     */
    public function destroy(HotelRoom $room): JsonResponse
    {
        if ($room->hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Room deleted successfully',
        ]);
    }

    /**
     * Clone a room.
     */
    /**
     * Clone a room.
     */
    public function clone(Request $request, HotelRoom $room): JsonResponse
    {
        if ($room->hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'clone_count' => 'required|integer'
        ]);

        $cloneCount = $validated['clone_count'];
        $clonedRooms = [];

        for ($i = 0; $i < $cloneCount; $i++) {
            $newRoom = $room->replicate();
            $newRoom->name .= ' (Copy ' . ($i + 1) . ')';
            $newRoom->push();

            foreach ($room->media as $media) {
                $newMedia = $media->replicate();
                $newMedia->room_id = $newRoom->id;
                $newMedia->push();
            }
            
            $clonedRooms[] = $newRoom->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Room cloned successfully ' . $cloneCount . ' times',
            'data' => ['cloned_rooms_ids' => $clonedRooms],
        ], 201);
    }

    /**
     * Bulk update rooms.
     */
    public function bulkUpdate(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'rooms' => 'required|array|min:1',
            'rooms.*.id' => 'required|exists:hotel_rooms,id',
            'rooms.*.price_per_night' => 'nullable|numeric|min:0',
            'rooms.*.beds_count' => 'nullable|integer|min:1',
            'rooms.*.bathrooms_count' => 'nullable|integer|min:1',
            'rooms.*.rooms_count' => 'nullable|integer|min:1',
            'rooms.*.is_active' => 'nullable|boolean',
            'rooms.*.services' => 'nullable|array',
            'rooms.*.services.*' => 'exists:services,id',
        ]);

        $updatedRooms = [];

        foreach ($validated['rooms'] as $roomData) {
            $room = HotelRoom::find($roomData['id']);

            // Ensure room belongs to the hotel
            if ($room->hotel_id !== $hotel->id) {
                continue;
            }

            $updateData = collect($roomData)->except(['id', 'services'])->filter()->toArray();
            
            if (!empty($updateData)) {
                $room->update($updateData);
            }

            if (isset($roomData['services'])) {
                $services = \App\Models\Service::whereIn('id', $roomData['services'])->get();
                $servicesData = $services->map(function ($service) {
                    return [
                        'id' => $service->id,
                        'name_ar' => $service->name_ar,
                        'name_en' => $service->name_en,
                        'image' => $service->image,
                    ];
                })->toArray();
                $room->services = $servicesData;
                $room->save();
            }

            $updatedRooms[] = $room->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Rooms updated successfully',
            'data' => ['updated_rooms_ids' => $updatedRooms],
        ]);
    }

    /**
     * Mass update rooms (update multiple rooms with same values).
     */
    public function massUpdate(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'required|exists:hotel_rooms,id',
            'price_per_night' => 'nullable|numeric|min:0',
            'beds_count' => 'nullable|integer|min:1',
            'bathrooms_count' => 'nullable|integer|min:1',
            'rooms_count' => 'nullable|integer|min:1',
            'is_active' => 'nullable|boolean',
            'services' => 'nullable|array',
            'services.*' => 'exists:services,id',
        ]);

        $updateData = collect($validated)->except(['room_ids', 'services'])->filter()->toArray();
        $updatedRooms = [];

        // Prepare services data if present
        $servicesData = null;
        if (isset($validated['services'])) {
            $services = \App\Models\Service::whereIn('id', $validated['services'])->get();
            $servicesData = $services->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name_ar' => $service->name_ar,
                    'name_en' => $service->name_en,
                    'image' => $service->image,
                ];
            })->toArray();
        }

        foreach ($validated['room_ids'] as $roomId) {
            $room = HotelRoom::find($roomId);

            if (!$room || $room->hotel_id !== $hotel->id) {
                continue;
            }

            if (!empty($updateData)) {
                $room->update($updateData);
            }

            if ($servicesData !== null) {
                $room->services = $servicesData;
                $room->save();
            }

            $updatedRooms[] = $room->id;
        }

        return response()->json([
            'success' => true,
            'message' => 'Rooms updated successfully',
            'data' => ['updated_rooms_ids' => $updatedRooms],
        ]);
    }

    /**
     * Delete multiple rooms.
     */
    public function bulkDelete(Request $request, Hotel $hotel): JsonResponse
    {
        if ($hotel->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'room_ids' => 'required|array|min:1',
            'room_ids.*' => 'required|exists:hotel_rooms,id',
        ]);

        $deletedCount = 0;
        foreach ($validated['room_ids'] as $roomId) {
            $room = HotelRoom::find($roomId);

            if (!$room || $room->hotel_id !== $hotel->id) {
                continue;
            }

            $room->delete();
            $deletedCount++;
        }

        return response()->json([
            'success' => true,
            'message' => $deletedCount . ' rooms deleted successfully',
        ]);
    }
}
