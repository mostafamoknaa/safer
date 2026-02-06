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
        $query = \App\Models\Trip::where('is_active', true)
            ->with(['bus']);

        /*
        // Filter by province - Trips use string locations, implementation pending if needed
        if ($request->filled('province_id')) {
            // $query->where('province_id', $request->province_id);
        }
        */

        // Filter by date
        if ($request->filled('date')) {
            $query->whereDate('trip_date', $request->date);
        }

        $trips = $query->orderBy('trip_date', 'asc')->orderBy('trip_time', 'asc')->get();

        return view('web.services.trips', compact('trips'));
    }

    /**
     * Display trip details.
     */
    public function showTrip(Trip $trip)
    {
        $trip->load(['bus']);

        return view('web.trips.show', compact('trip'));
    }

    /**
     * Display private cars.
     */
    public function privateCars()
    {
        $privateCars = PrivateCar::where('is_active', true)->with('media')->get();

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
        $totalPrice = $trip->price * $validated['number_of_seats'];

        ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type' => 'bus',
            'trip_id' => $validated['trip_id'],
            'bus_id' => $trip->bus_id,
            'passengers_count' => $validated['number_of_seats'],
            'departure_location_ar' => $validated['pickup_location'],
            'departure_location_en' => $validated['pickup_location'],
            'arrival_location_ar' => $trip->arrival_location_ar,
            'arrival_location_en' => $trip->arrival_location_en,
            'trip_date' => $trip->trip_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => $validated['notes'],
        ]);

        return redirect()->route('web.bookings.index', ['tab' => 'services'])
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
            'pickup_date' => 'required|date|after_or_equal:today',
            'pickup_time' => 'nullable|date_format:H:i',
            'return_date' => 'nullable|date|after_or_equal:pickup_date',
            'booking_type' => 'required|in:hours,days',
            'duration_hours' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ], [
            'pickup_date.after_or_equal' => 'تاريخ الاستلام يجب أن يكون اليوم أو تاريخاً لاحقاً.',
            'return_date.after_or_equal' => 'تاريخ التسليم يجب أن يكون نفس تاريخ الاستلام أو بعده.',
            'pickup_location.required' => 'يرجى تحديد موقع الاستلام.',
            'destination.required' => 'يرجى تحديد جهة الوصول.',
        ], [
            'pickup_date' => 'تاريخ الاستلام',
            'pickup_time' => 'وقت الاستلام',
            'return_date' => 'تاريخ التسليم',
            'pickup_location' => 'موقع الاستلام',
            'destination' => 'جهة الوصول',
        ]);

        try {
            $privateCar = PrivateCar::findOrFail($validated['private_car_id']);

            $totalPrice = 0;
            if ($validated['booking_type'] === 'days') {
                $pickupDate = \Carbon\Carbon::parse($validated['pickup_date']);
                $returnDate = \Carbon\Carbon::parse($validated['return_date'] ?? $validated['pickup_date']);
                $days = max(1, $pickupDate->diffInDays($returnDate));
                $totalPrice = (float) $privateCar->price_per_day * $days;
            } else {
                $hours = $validated['duration_hours'] ?? 1;
                $pricePerHour = (float) ($privateCar->price_per_hour ?? ($privateCar->price_per_day / 24));
                $totalPrice = $pricePerHour * $hours;
            }

            ServiceRequest::create([
                'user_id' => auth()->id(),
                'service_type' => 'private_car',
                'private_car_id' => $validated['private_car_id'],
                'departure_location_ar' => $validated['pickup_location'],
                'departure_location_en' => $validated['pickup_location'],
                'arrival_location_ar' => $validated['destination'],
                'arrival_location_en' => $validated['destination'],
                'start_date' => $validated['pickup_date'],
                'start_time' => $validated['pickup_time'] ?? null,
                'booking_type' => $validated['booking_type'],
                'duration_hours' => $validated['booking_type'] === 'hours' ? ($validated['duration_hours'] ?? 1) : null,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return redirect()->route('web.bookings.index', ['tab' => 'services'])
                ->with('success', 'تم إرسال طلب السيارة بنجاح');
        } catch (\Exception $e) {
            \Log::error('Private car request failed: ' . $e->getMessage());
            return back()->with('error', 'حدث خطأ أثناء إرسال الطلب: ' . $e->getMessage())->withInput();
        }
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
