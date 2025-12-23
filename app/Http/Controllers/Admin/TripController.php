<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    /**
     * Display a listing of trips.
     */
    public function index(Request $request): View
    {
        $query = Trip::with('bus')->orderByDesc('trip_date');

        if ($request->filled('date')) {
            $query->whereDate('trip_date', $request->date);
        }

        if ($request->filled('bus_id')) {
            $query->where('bus_id', $request->bus_id);
        }

        $trips = $query->paginate(15)->withQueryString();
        $buses = Bus::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.trips.index', compact('trips', 'buses'));
    }

    /**
     * Show the form for creating a new trip.
     */
    public function create(): View
    {
        $buses = Bus::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.trips.create', compact('buses'));
    }

    /**
     * Store a newly created trip.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'price' => 'required|numeric|min:0',
            'trip_date' => 'required|date|after_or_equal:today',
            'trip_time' => ['required', 'string', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'is_active' => 'boolean',
        ]);

        Trip::create($validated);

        return redirect()
            ->route('admin.trips.index')
            ->with('success', __('admin.trips.messages.created'));
    }

    /**
     * Show the form for editing the specified trip.
     */
    public function edit(Trip $trip): View
    {
        $buses = Bus::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.trips.edit', compact('trip', 'buses'));
    }

    /**
     * Update the specified trip.
     */
    public function update(Request $request, Trip $trip): RedirectResponse
    {
        $validated = $request->validate([
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'price' => 'required|numeric|min:0',
            'trip_date' => 'required|date|after_or_equal:today',
            'trip_time' => ['required', 'string', 'regex:/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/'],
            'duration_minutes' => 'required|integer|min:1|max:1440',
            'is_active' => 'boolean',
        ]);

        $trip->update($validated);

        return redirect()
            ->route('admin.trips.index')
            ->with('success', __('admin.trips.messages.updated'));
    }

    /**
     * Remove the specified trip.
     */
    public function destroy(Trip $trip): RedirectResponse
    {
        // Check if trip has service requests
        if ($trip->serviceRequests()->count() > 0) {
            return redirect()
                ->route('admin.trips.index')
                ->with('error', __('admin.trips.messages.cannot_delete_with_requests'));
        }

        $trip->delete();

        return redirect()
            ->route('admin.trips.index')
            ->with('success', __('admin.trips.messages.deleted'));
    }
}
