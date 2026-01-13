<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\PrivateCar;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    /**
     * Display buses.
     */
    public function buses()
    {
        $buses = Bus::where('is_active', true)
            ->with('trips')
            ->get();

        return view('web.services.buses', compact('buses'));
    }

    /**
     * Display trips.
     */
    public function trips(Request $request)
    {
        $query = Trip::where('is_active', true)
            ->with(['bus', 'province']);

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('departure_time', $request->date);
        }

        $trips = $query->orderBy('departure_time', 'asc')->get();

        return view('web.services.trips', compact('trips'));
    }

    /**
     * Display trip details.
     */
    public function showTrip(Trip $trip)
    {
        $trip->load(['bus', 'province']);

        return view('web.services.trip-details', compact('trip'));
    }

    /**
     * Display private cars.
     */
    public function privateCars()
    {
        $privateCars = PrivateCar::where('is_active', true)->get();

        return view('web.services.private-cars', compact('privateCars'));
    }

    /**
     * Create bus request.
     */
    public function createBusRequest(Request $request)
    {
        $validated = $request->validate([
            'trip_id' => 'required|exists:trips,id',
            'number_of_seats' => 'required|integer|min:1',
            'pickup_location' => 'required|string',
            'notes' => 'nullable|string',
        ]);

        $trip = Trip::findOrFail($validated['trip_id']);

        // Calculate total price
        $totalPrice = $trip->price_per_seat * $validated['number_of_seats'];

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type' => 'bus',
            'trip_id' => $validated['trip_id'],
            'number_of_seats' => $validated['number_of_seats'],
            'pickup_location' => $validated['pickup_location'],
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('web.services.my-requests')
            ->with('success', 'تم إرسال طلب الحجز بنجاح');
    }

    /**
     * Create private car request.
     */
    public function createPrivateCarRequest(Request $request)
    {
        $validated = $request->validate([
            'private_car_id' => 'required|exists:private_cars,id',
            'pickup_location' => 'required|string',
            'destination' => 'required|string',
            'pickup_date' => 'required|date|after:today',
            'notes' => 'nullable|string',
        ]);

        $privateCar = PrivateCar::findOrFail($validated['private_car_id']);

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type' => 'private_car',
            'private_car_id' => $validated['private_car_id'],
            'pickup_location' => $validated['pickup_location'],
            'destination' => $validated['destination'],
            'pickup_date' => $validated['pickup_date'],
            'total_price' => $privateCar->price_per_day,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('web.services.my-requests')
            ->with('success', 'تم إرسال طلب السيارة بنجاح');
    }

    /**
     * Display user's service requests.
     */
    public function myRequests()
    {
        $requests = auth()->user()->serviceRequests()
            ->with(['trip.bus', 'privateCar'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.services.my-requests', compact('requests'));
    }
}
