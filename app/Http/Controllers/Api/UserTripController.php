<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\Bus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserTripController extends Controller
{
    /**
     * Get user's trips.
     */
    public function index(): JsonResponse
    {
        $trips = Trip::where('user_id', Auth::id())
            ->with('bus')
            ->orderBy('trip_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $trips,
        ]);
    }

    /**
     * Store a new trip.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'price' => 'required|numeric|min:0',
            'trip_date' => 'required|date',
            'trip_time' => 'required',
            'duration_minutes' => 'required|integer|min:1',
        ]);

        // Verify bus ownership
        $bus = Bus::findOrFail($validated['bus_id']);
        if ($bus->user_id !== Auth::id()) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid bus selection. You do not own this bus.'
            ], 403);
        }

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        $trip = Trip::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Trip added successfully',
            'data' => $trip,
        ], 201);
    }

    /**
     * Update a trip.
     */
    public function update(Request $request, Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'departure_location_ar' => 'string|max:255',
            'departure_location_en' => 'string|max:255',
            'arrival_location_ar' => 'string|max:255',
            'arrival_location_en' => 'string|max:255',
            'bus_id' => 'exists:buses,id',
            'price' => 'numeric|min:0',
            'trip_date' => 'date',
            'trip_time' => 'string',
            'duration_minutes' => 'integer|min:1',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['bus_id'])) {
            $bus = Bus::findOrFail($validated['bus_id']);
            if ($bus->user_id !== Auth::id()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Invalid bus selection. You do not own this bus.'
                ], 403);
            }
        }

        $trip->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Trip updated successfully',
            'data' => $trip,
        ]);
    }

    /**
     * Delete a trip.
     */
    public function destroy(Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $trip->delete();

        return response()->json([
            'success' => true,
            'message' => 'Trip deleted successfully',
        ]);
    }

    /**
     * Clone a trip.
     */
    public function clone(Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newTrip = $trip->replicate();
        $newTrip->push();

        return response()->json([
            'success' => true,
            'message' => 'Trip cloned successfully',
            'data' => $newTrip,
        ], 201);
    }
}
