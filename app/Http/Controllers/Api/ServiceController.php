<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\PrivateCar;
use App\Models\ServiceRequest;
use App\Models\BusSeat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    /**
     * Get available buses.
     */
    public function getBuses(): JsonResponse
    {
        $buses = Bus::where('is_active', true)
            ->select('id', 'name_ar', 'name_en', 'total_seats', 'type')
            ->get()
            ->map(function ($bus) {
                // Determine bus size and numbering system
                $busSize = $bus->total_seats > 30 ? 'large' : 'small';
                $numberingSystem = $busSize === 'large' ? 'alphanumeric' : 'numeric';
                
                return [
                    'id' => $bus->id,
                    'name' => app()->getLocale() === 'ar' ? $bus->name_ar : $bus->name_en,
                    'total_seats' => $bus->total_seats,
                    'size' => $busSize,
                    'numbering_system' => $numberingSystem,
                    'type' => $bus->type ?? 'standard',
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $buses,
        ]);
    }

    /**
     * Get available trips.
     */
    public function getTrips(Request $request): JsonResponse
    {
        $query = Trip::with('bus')
            ->where('is_active', true)
            ->whereDate('trip_date', '>=', now());

        if ($request->filled('departure_location')) {
            $search = $request->departure_location;
            $query->where(function ($q) use ($search) {
                $q->where('departure_location_ar', 'like', "%{$search}%")
                    ->orWhere('departure_location_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('arrival_location')) {
            $search = $request->arrival_location;
            $query->where(function ($q) use ($search) {
                $q->where('arrival_location_ar', 'like', "%{$search}%")
                    ->orWhere('arrival_location_en', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('trip_date', $request->date);
        }

        $trips = $query->orderBy('trip_date')
            ->get()
            ->map(function ($trip) {
                return [
                    'id' => $trip->id,
                    'bus_id' => $trip->bus_id,
                    'bus_name' => app()->getLocale() === 'ar' ? $trip->bus->name_ar : $trip->bus->name_en,
                    'departure_location' => app()->getLocale() === 'ar' ? $trip->departure_location_ar : $trip->departure_location_en,
                    'arrival_location' => app()->getLocale() === 'ar' ? $trip->arrival_location_ar : $trip->arrival_location_en,
                    'price' => (float) $trip->price,
                    'trip_date' => $trip->trip_date->format('Y-m-d'),
                    'trip_time' => $trip->trip_time,
                    'duration_minutes' => $trip->duration_minutes,
                    'available_seats' => $trip->available_seats_count,
                    'total_seats' => $trip->bus->total_seats,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $trips,
        ]);
    }

    /**
     * Get trip details with available seats.
     */
    public function getTripDetails(Trip $trip): JsonResponse
    {
        $trip->load('bus');
        $bookedSeats = $trip->booked_seat_numbers;

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $trip->id,
                'bus' => [
                    'id' => $trip->bus->id,
                    'name' => app()->getLocale() === 'ar' ? $trip->bus->name_ar : $trip->bus->name_en,
                    'total_seats' => $trip->bus->total_seats,
                ],
                'departure_location' => app()->getLocale() === 'ar' ? $trip->departure_location_ar : $trip->departure_location_en,
                'arrival_location' => app()->getLocale() === 'ar' ? $trip->arrival_location_ar : $trip->arrival_location_en,
                'price' => (float) $trip->price,
                'trip_date' => $trip->trip_date->format('Y-m-d'),
                'trip_time' => $trip->trip_time,
                'duration_minutes' => $trip->duration_minutes,
                'available_seats' => $trip->available_seats_count,
                'booked_seats' => $bookedSeats,
            ],
        ]);
    }

    /**
     * Get available private cars.
     */
    public function getPrivateCars(): JsonResponse
    {
        $cars = PrivateCar::where('is_active', true)
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'name' => app()->getLocale() === 'ar' ? $car->name_ar : $car->name_en,
                    'hourly_rate' => (float) $car->price,
                    'price_per_hour' => (float) $car->price,
                    'seats_count' => $car->seats_count,
                    'image' => $car->image_url,
                    'max_speed' => $car->max_speed,
                    'acceleration' => $car->acceleration ? (float) $car->acceleration : null,
                    'power' => $car->power,
                    'fuel_type' => $car->fuel_type ?? 'gasoline',
                    'transmission' => $car->transmission ?? 'automatic',
                    'notes' => app()->getLocale() === 'ar' ? $car->notes_ar : $car->notes_en,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $cars,
        ]);
    }

    /**
     * Create bus service request.
     */
    public function createBusRequest(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'trip_id' => 'required|exists:trips,id',
                'passengers_count' => 'required|integer|min:1|max:100',
                'selected_seats' => 'nullable|array|max:100',
                'selected_seats.*' => 'integer|min:1',
                'notes' => 'nullable|string|max:1000',
            ]);

            $trip = Trip::with('bus')->findOrFail($validated['trip_id']);

            // Check available seats
            if ($validated['passengers_count'] > $trip->available_seats_count) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.services.not_enough_seats'),
                ], 400);
            }

            // Validate selected seats if provided
            if (!empty($validated['selected_seats'])) {
                $bookedSeats = $trip->booked_seat_numbers;
                foreach ($validated['selected_seats'] as $seatNumber) {
                    if (in_array($seatNumber, $bookedSeats)) {
                        return response()->json([
                            'success' => false,
                            'message' => __('api.services.seat_already_booked', ['seat' => $seatNumber]),
                        ], 400);
                    }
                    if ($seatNumber > $trip->bus->total_seats || $seatNumber < 1) {
                        return response()->json([
                            'success' => false,
                            'message' => __('api.services.invalid_seat_number'),
                        ], 400);
                    }
                }
            }

            // Calculate total price
            $totalPrice = $trip->price * $validated['passengers_count'];

            // Create service request
            $serviceRequest = ServiceRequest::create([
                'user_id' => Auth::id(),
                'service_type' => 'bus',
                'trip_id' => $trip->id,
                'bus_id' => $trip->bus_id,
                'departure_location_ar' => $trip->departure_location_ar,
                'departure_location_en' => $trip->departure_location_en,
                'arrival_location_ar' => $trip->arrival_location_ar,
                'arrival_location_en' => $trip->arrival_location_en,
                'passengers_count' => $validated['passengers_count'],
                'trip_date' => $trip->trip_date,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            // Book selected seats
            if (!empty($validated['selected_seats'])) {
                foreach ($validated['selected_seats'] as $seatNumber) {
                    BusSeat::create([
                        'service_request_id' => $serviceRequest->id,
                        'trip_id' => $trip->id,
                        'seat_number' => $seatNumber,
                    ]);
                }
            }

            return response()->json([
                'success' => true,
                'message' => __('api.services.request_created'),
                'data' => [
                    'request_id' => $serviceRequest->id,
                    'request_reference' => $serviceRequest->request_reference,
                    'total_price' => $totalPrice,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Create private car service request.
     */
    public function createPrivateCarRequest(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'private_car_id' => 'required|exists:private_cars,id',
                'duration_hours' => 'required|integer|min:1|max:8760',
                'start_date' => 'required|date|after_or_equal:today',
                'notes' => 'nullable|string|max:1000',
            ]);

            $car = PrivateCar::findOrFail($validated['private_car_id']);

            // Calculate total price (price is per hour, duration_hours is the rental duration)
            $totalPrice = $car->price * $validated['duration_hours'];

            // Create service request
            $serviceRequest = ServiceRequest::create([
                'user_id' => Auth::id(),
                'service_type' => 'private_car',
                'private_car_id' => $car->id,
                'duration_hours' => $validated['duration_hours'],
                'start_date' => $validated['start_date'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('api.services.request_created'),
                'data' => [
                    'request_id' => $serviceRequest->id,
                    'request_reference' => $serviceRequest->request_reference,
                    'total_price' => $totalPrice,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get user service requests.
     */
    public function getUserRequests(): JsonResponse
    {
        $requests = ServiceRequest::where('user_id', Auth::id())
            ->with(['trip', 'trip.bus', 'bus', 'privateCar'])
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($request) {
                $data = [
                    'id' => $request->id,
                    'request_reference' => $request->request_reference,
                    'service_type' => $request->service_type,
                    'total_price' => (float) $request->total_price,
                    'status' => $request->status,
                    'created_at' => $request->created_at->format('Y-m-d H:i:s'),
                ];

                if ($request->service_type === 'bus') {
                    $data['trip'] = $request->trip ? [
                        'departure_location' => app()->getLocale() === 'ar' ? $request->trip->departure_location_ar : $request->trip->departure_location_en,
                        'arrival_location' => app()->getLocale() === 'ar' ? $request->trip->arrival_location_ar : $request->trip->arrival_location_en,
                        'trip_date' => $request->trip->trip_date->format('Y-m-d'),
                    ] : null;
                    $data['passengers_count'] = $request->passengers_count;
                } else {
                    $data['car'] = $request->privateCar ? [
                        'name' => app()->getLocale() === 'ar' ? $request->privateCar->name_ar : $request->privateCar->name_en,
                    ] : null;
                    $data['duration_hours'] = $request->duration_hours;
                    $data['start_date'] = $request->start_date ? $request->start_date->format('Y-m-d') : null;
                }

                return $data;
            });

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }
}
