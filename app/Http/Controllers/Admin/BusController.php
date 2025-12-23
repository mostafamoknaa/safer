<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusController extends Controller
{
    /**
     * Display a listing of buses.
     */
    public function index(): View
    {
        $buses = Bus::withCount('trips')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('admin.buses.index', compact('buses'));
    }

    /**
     * Show the form for creating a new bus.
     */
    public function create(): View
    {
        return view('admin.buses.create');
    }

    /**
     * Store a newly created bus.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1|max:200',
            'is_active' => 'boolean',
        ]);

        Bus::create($validated);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', __('admin.buses.messages.created'));
    }

    /**
     * Show the form for editing the specified bus.
     */
    public function edit(Bus $bus): View
    {
        return view('admin.buses.edit', compact('bus'));
    }

    /**
     * Update the specified bus.
     */
    public function update(Request $request, Bus $bus): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1|max:200',
            'is_active' => 'boolean',
        ]);

        $bus->update($validated);

        return redirect()
            ->route('admin.buses.index')
            ->with('success', __('admin.buses.messages.updated'));
    }

    /**
     * Remove the specified bus.
     */
    public function destroy(Bus $bus): RedirectResponse
    {
        // Check if bus has trips
        if ($bus->trips()->count() > 0) {
            return redirect()
                ->route('admin.buses.index')
                ->with('error', __('admin.buses.messages.cannot_delete_with_trips'));
        }

        $bus->delete();

        return redirect()
            ->route('admin.buses.index')
            ->with('success', __('admin.buses.messages.deleted'));
    }
}
