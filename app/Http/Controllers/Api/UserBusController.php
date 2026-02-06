<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserBusController extends Controller
{
    /**
     * Get user's buses.
     */
    public function index(): JsonResponse
    {
        $buses = Bus::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $buses,
        ]);
    }

    /**
     * Store a new bus.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
        ]);

        $validated['user_id'] = Auth::id();
        $validated['is_active'] = false; // Pending approval

        $bus = Bus::create($validated);

        // Notify
        app(\App\Services\FirebaseNotificationService::class)->notifySubmission($bus->user, $bus->name_ar ?: $bus->name_en, 'bus');


        return response()->json([
            'success' => true,
            'message' => 'Bus added successfully',
            'data' => $bus,
        ], 201);
    }

    /**
     * Update a bus.
     */
    public function update(Request $request, Bus $bus): JsonResponse
    {
        if ($bus->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name_ar' => 'string|max:255',
            'name_en' => 'string|max:255',
            'total_seats' => 'integer|min:1',
            'type' => 'string|max:255',
            'is_active' => 'boolean',
        ]);

        $bus->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Bus updated successfully',
            'data' => $bus,
        ]);
    }

    /**
     * Delete a bus.
     */
    public function destroy(Bus $bus): JsonResponse
    {
        if ($bus->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($bus->trips()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'لا يمكن حذف الباص لأنه مرتبط برحلات',
            ], 400);
        }

        $bus->delete();

        return response()->json([
            'success' => true,
            'message' => 'تم حذف الباص بنجاح',
        ]);
    }

    /**
     * Clone a bus.
     */
    public function clone(Bus $bus): JsonResponse
    {
        if ($bus->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $newBus = $bus->replicate();
        $newBus->name_en .= ' (Copy)';
        $newBus->name_ar .= ' (نسخة)';
        $newBus->push();

        return response()->json([
            'success' => true,
            'message' => 'Bus cloned successfully',
            'data' => $newBus,
        ], 201);
    }
}
