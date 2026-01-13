<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrivateCar;
use App\Models\PrivateCarMedia; // Assuming relationships logic
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserCarController extends Controller
{
    /**
     * Get user's cars.
     */
    public function index(): JsonResponse
    {
        $cars = PrivateCar::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cars,
        ]);
    }

    /**
     * Store a new car.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'required|numeric|min:0',
            'seats_count' => 'required|integer|min:1',
            'max_speed' => 'required|integer|min:0',
            'acceleration' => 'required|numeric|min:0',
            'power' => 'required|integer|min:0',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = true;

        $car = PrivateCar::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Car added successfully',
            'data' => $car,
        ], 201);
    }

    /**
     * Update a car.
     */
    public function update(Request $request, PrivateCar $car): JsonResponse
    {
        if ($car->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name_ar' => 'string|max:255',
            'name_en' => 'string|max:255',
            'car_model' => 'string|max:255',
            'price_per_day' => 'numeric|min:0',
            'price_per_hour' => 'numeric|min:0',
            'seats_count' => 'integer|min:1',
            'max_speed' => 'integer|min:0',
            'acceleration' => 'numeric|min:0',
            'power' => 'integer|min:0',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $car->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $car,
        ]);
    }

    /**
     * Delete a car.
     */
    public function destroy(PrivateCar $car): JsonResponse
    {
        if ($car->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $car->delete();

        return response()->json([
            'success' => true,
            'message' => 'Car deleted successfully',
        ]);
    }

    /**
     * Clone a car.
     */
    public function clone(PrivateCar $car): JsonResponse
    {
        if ($car->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newCar = $car->replicate();
        $newCar->name_en .= ' (Copy)';
        $newCar->name_ar .= ' (نسخة)';
        $newCar->push();

        return response()->json([
            'success' => true,
            'message' => 'Car cloned successfully',
            'data' => $newCar,
        ], 201);
    }
}
