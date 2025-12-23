<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrivateCar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PrivateCarController extends Controller
{
    /**
     * Display a listing of private cars.
     */
    public function index(): View
    {
        $cars = PrivateCar::withCount('serviceRequests')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.private-cars.index', compact('cars'));
    }

    /**
     * Show the form for creating a new private car.
     */
    public function create(): View
    {
        return view('admin.private-cars.create');
    }

    /**
     * Store a newly created private car.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'seats_count' => 'required|integer|min:1|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'max_speed' => 'nullable|integer|min:0|max:500',
            'acceleration' => 'nullable|numeric|min:0|max:100',
            'power' => 'nullable|integer|min:0|max:2000',
            'notes_ar' => 'nullable|string|max:2000',
            'notes_en' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('private-cars', 'public');
        }

        PrivateCar::create($validated);

        return redirect()
            ->route('admin.private-cars.index')
            ->with('success', __('admin.private_cars.messages.created'));
    }

    /**
     * Show the form for editing the specified private car.
     */
    public function edit(PrivateCar $privateCar): View
    {
        return view('admin.private-cars.edit', compact('privateCar'));
    }

    /**
     * Update the specified private car.
     */
    public function update(Request $request, PrivateCar $privateCar): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0|max:999999.99',
            'seats_count' => 'required|integer|min:1|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:10240',
            'max_speed' => 'nullable|integer|min:0|max:500',
            'acceleration' => 'nullable|numeric|min:0|max:100',
            'power' => 'nullable|integer|min:0|max:2000',
            'notes_ar' => 'nullable|string|max:2000',
            'notes_en' => 'nullable|string|max:2000',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image
            if ($privateCar->image) {
                Storage::disk('public')->delete($privateCar->image);
            }
            $validated['image'] = $request->file('image')->store('private-cars', 'public');
        }

        $privateCar->update($validated);

        return redirect()
            ->route('admin.private-cars.index')
            ->with('success', __('admin.private_cars.messages.updated'));
    }

    /**
     * Remove the specified private car.
     */
    public function destroy(PrivateCar $privateCar): RedirectResponse
    {
        // Check if car has service requests
        if ($privateCar->serviceRequests()->count() > 0) {
            return redirect()
                ->route('admin.private-cars.index')
                ->with('error', __('admin.private_cars.messages.cannot_delete_with_requests'));
        }

        // Delete image
        if ($privateCar->image) {
            Storage::disk('public')->delete($privateCar->image);
        }

        $privateCar->delete();

        return redirect()
            ->route('admin.private-cars.index')
            ->with('success', __('admin.private_cars.messages.deleted'));
    }
}
