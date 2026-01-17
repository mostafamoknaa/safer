<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TripController extends Controller
{
    public function index(): View
    {
        $trips = Trip::where('user_id', auth()->id())
            ->with('bus')
            ->withCount('serviceRequests')
            ->orderByDesc('trip_date')
            ->orderByDesc('trip_time')
            ->paginate(15);

        return view('hotel.trips.index', compact('trips'));
    }

    public function create(): View
    {
        $buses = Bus::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('hotel.trips.create', compact('buses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'price' => 'required|numeric|min:0',
            'trip_date' => 'required|date',
            'trip_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $bus = Bus::findOrFail($data['bus_id']);
        if ($bus->user_id !== auth()->id()) {
            abort(403);
        }

        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active');

        Trip::create($data);

        return redirect()->route('hotel.trips.index')->with('success', 'Trip created successfully');
    }

    public function edit(Trip $trip): View
    {
        if ($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $buses = Bus::where('user_id', auth()->id())
            ->where('is_active', true)
            ->get();

        return view('hotel.trips.edit', compact('trip', 'buses'));
    }

    public function update(Request $request, Trip $trip): RedirectResponse
    {
        if ($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'bus_id' => 'required|exists:buses,id',
            'price' => 'required|numeric|min:0',
            'trip_date' => 'required|date',
            'trip_time' => 'required|date_format:H:i',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'sometimes|boolean',
        ]);

        $bus = Bus::findOrFail($data['bus_id']);
        if ($bus->user_id !== auth()->id()) {
            abort(403);
        }

        $data['is_active'] = $request->boolean('is_active');

        $trip->update($data);

        return redirect()->route('hotel.trips.index')->with('success', 'Trip updated successfully');
    }

    public function destroy(Trip $trip): RedirectResponse
    {
        if ($trip->user_id !== auth()->id()) {
            abort(403);
        }

        $trip->delete();

        return redirect()->route('hotel.trips.index')->with('success', 'Trip deleted successfully');
    }
}
