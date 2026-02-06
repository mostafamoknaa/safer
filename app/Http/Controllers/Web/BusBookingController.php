<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class BusBookingController extends Controller
{
    /**
     * Display bus search form.
     */
    public function search(Request $request)
    {
        return view('web.buses.search', [
            'departure_location' => $request->query('departure_location'),
            'arrival_location' => $request->query('arrival_location'),
            'trip_date' => $request->query('trip_date'),
            'number_of_passengers' => $request->query('number_of_passengers', 1),
        ]);
    }

    /**
     * Show available buses based on search criteria.
     */
    public function searchResults(Request $request)
    {
        $validated = $request->validate([
            'departure_location' => 'required|string',
            'arrival_location' => 'required|string',
            'trip_date' => 'required|date|after_or_equal:today',
            'number_of_passengers' => 'required|integer|min:1',
        ]);

        // Search for trips matching criteria
        $trips = Trip::where('is_active', true)
            ->where(function ($query) use ($validated) {
                $query->where('departure_location_ar', 'like', '%' . $validated['departure_location'] . '%')
                    ->orWhere('departure_location_en', 'like', '%' . $validated['departure_location'] . '%');
            })
            ->where(function ($query) use ($validated) {
                $query->where('arrival_location_ar', 'like', '%' . $validated['arrival_location'] . '%')
                    ->orWhere('arrival_location_en', 'like', '%' . $validated['arrival_location'] . '%');
            })
            ->whereDate('trip_date', $validated['trip_date'])
            ->with(['bus'])
            ->whereHas('bus', function ($query) use ($validated) {
                $query->where('total_seats', '>=', $validated['number_of_passengers']);
            })
            ->orderBy('trip_time', 'asc')
            ->get();

        return view('web.buses.available', [
            'trips' => $trips,
            'searchData' => $validated
        ]);
    }

    /**
     * Display seat selection page.
     */
    public function selectSeat(Request $request, Trip $trip)
    {
        $trip->load('bus');

        $numberOfPassengers = $request->input('number_of_passengers', 1);

        return view('web.buses.select-seat', [
            'trip' => $trip,
            'numberOfPassengers' => $numberOfPassengers
        ]);
    }

    /**
     * Confirm seat selection and proceed to payment.
     */
    public function confirmSeat(Request $request, Trip $trip)
    {
        $validated = $request->validate([
            'selected_seats' => 'required', // Accept JSON string or array
            'number_of_passengers' => 'required|integer|min:1',
            'payment_method' => 'nullable|in:mastercard,visa',
        ]);

        // Decode selected_seats if it's a JSON string
        $selectedSeats = is_string($validated['selected_seats'])
            ? json_decode($validated['selected_seats'], true)
            : $validated['selected_seats'];

        if (!is_array($selectedSeats)) {
            return back()->withErrors(['selected_seats' => 'Invalid seat selection']);
        }

        $totalPrice = $trip->price * count($selectedSeats);

        // Created booking with pending status
        $serviceRequest = ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type' => 'bus',
            'trip_id' => $trip->id,
            'bus_id' => $trip->bus_id,
            'passengers_count' => $validated['number_of_passengers'],
            'departure_location_ar' => $trip->departure_location_ar,
            'departure_location_en' => $trip->departure_location_en,
            'arrival_location_ar' => $trip->arrival_location_ar,
            'arrival_location_en' => $trip->arrival_location_en,
            'trip_date' => $trip->trip_date,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'notes' => 'المقاعد المحجوزة: ' . implode(', ', $selectedSeats),
        ]);

        foreach ($selectedSeats as $seatNum) {
            \App\Models\BusSeat::create([
                'service_request_id' => $serviceRequest->id,
                'trip_id' => $trip->id,
                'seat_number' => $seatNum
            ]);
        }

        return redirect()->route('web.bookings.service_show', $serviceRequest->id)
            ->with('success', 'تم الحجز بنجاح، بانتظار موافقة الإدارة لتفعيل خيار الدفع');
    }

    /**
     * @deprecated Old payment behavior removed
     */
    public function payment()
    {
        if (!session()->has('bus_booking')) {
            return redirect()->route('web.buses.search')
                ->with('error', 'الرجاء اختيار رحلة أولاً');
        }

        $bookingData = session('bus_booking');
        $trip = Trip::with('bus')->findOrFail($bookingData['trip_id']);

        return view('web.buses.payment', [
            'trip' => $trip,
            'bookingData' => $bookingData
        ]);
    }

    /**
     * Process payment and create booking.
     */
    public function processPayment(Request $request)
    {
        if (!session()->has('bus_booking')) {
            return redirect()->route('web.buses.search')
                ->with('error', 'الرجاء اختيار رحلة أولاً');
        }

        $validated = $request->validate([
            'payment_method' => 'required|in:mastercard,visa',
        ]);

        $bookingData = session('bus_booking');
        $trip = Trip::findOrFail($bookingData['trip_id']);

        // Create service request
        $serviceRequest = ServiceRequest::create([
            'user_id' => auth()->id(),
            'service_type' => 'bus',
            'trip_id' => $trip->id,
            'number_of_seats' => $bookingData['number_of_passengers'],
            'total_price' => $bookingData['total_price'],
            'status' => 'confirmed',
            'payment_method' => $validated['payment_method'],
            'notes' => 'المقاعد المحجوزة: ' . implode(', ', $bookingData['selected_seats']),
        ]);

        // Clear session
        session()->forget('bus_booking');

        return redirect()->route('web.buses.confirmation', $serviceRequest->id);
    }

    /**
     * Display booking confirmation.
     */
    public function confirmation(ServiceRequest $serviceRequest)
    {
        if ($serviceRequest->user_id !== auth()->id()) {
            abort(403);
        }

        $serviceRequest->load(['trip.bus']);

        return view('web.buses.confirmation', [
            'serviceRequest' => $serviceRequest
        ]);
    }
}
