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
            ->with('media')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'name_ar' => $car->name_ar,
                    'name_en' => $car->name_en,
                    'car_model' => $car->car_model,
                    'price_per_day' => (float) $car->price_per_day,
                    'price_per_hour' => (float) $car->price_per_hour,
                    'seats_count' => $car->seats_count,
                    'max_speed' => $car->max_speed,
                    'acceleration' => $car->acceleration ? (float) $car->acceleration : null,
                    'power' => $car->power,
                    'notes_ar' => $car->notes_ar,
                    'notes_en' => $car->notes_en,
                    'is_active' => $car->is_active,
                    'images' => $car->media->map(fn($media) => asset('storage/' . $media->file_path)),
                ];
            });

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
            'cancellation_policy' => 'nullable|array',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = false; // Pending approval

        $car = PrivateCar::create($validated);

        // Notify
        app(\App\Services\FirebaseNotificationService::class)->notifySubmission($car->user, $car->name_ar ?: $car->name_en, 'car');

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $index => $image) {
                $path = $image->store('private-cars', 'public');
                PrivateCarMedia::create([
                    'private_car_id' => $car->id,
                    'file_path' => $path,
                    'order_column' => $index,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Car added successfully',
            'data' => $car->load('media'),
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
            'cancellation_policy' => 'nullable|array',
            'is_active' => 'boolean',
            'images' => 'nullable|array|max:10',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $car->update($validated);

        // Handle new images
        if ($request->hasFile('images')) {
            $orderColumn = $car->media()->max('order_column') ?? -1;
            foreach ($request->file('images') as $image) {
                $path = $image->store('private-cars', 'public');
                PrivateCarMedia::create([
                    'private_car_id' => $car->id,
                    'file_path' => $path,
                    'order_column' => ++$orderColumn,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Car updated successfully',
            'data' => $car->load('media'),
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
