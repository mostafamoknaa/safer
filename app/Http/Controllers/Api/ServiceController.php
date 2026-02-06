<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\PrivateCar;
use App\Models\ServiceRequest;
use App\Models\BusSeat;
use App\Models\ClosedSeat;
use App\Models\Service;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ServiceController extends Controller
{
    use \App\Traits\HandlesPayments;

    /**
     * Get master services.
     */
    public function getMasterServices(): JsonResponse
    {
        $services = Service::where('is_active', true)
            ->select('id', 'name_ar', 'name_en', 'image')
            ->get()
            ->map(function ($service) {
                return [
                    'id' => $service->id,
                    'name' => app()->getLocale() === 'ar' ? $service->name_ar : $service->name_en,
                    'image' => $service->image ? asset('storage/' . $service->image) : null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $services,
        ]);
    }

    /**
     * Get available buses.
     */
    public function getBuses(): JsonResponse
    {
        $buses = Bus::where('is_active', true)
            ->with(['trips' => function($query) {
                $query->whereDate('trip_date', '>=', now())
                      ->with(['bookedSeats']);
            }])
            ->select('id', 'name_ar', 'name_en', 'total_seats', 'type')
            ->get()
            ->map(function ($bus) {
                // Get all reserved seats for this bus from current and future trips
                $reservedSeats = $bus->trips->flatMap(function($trip) {
                    return $trip->bookedSeats->pluck('seat_number');
                })->unique()->values()->toArray();
                
                // Determine bus size and numbering system
                $busSize = $bus->total_seats > 30 ? 'large' : 'small';
                $numberingSystem = $busSize === 'large' ? 'alphanumeric' : 'numeric';
                
                return [
                    'id' => $bus->id,
                    'name' => app()->getLocale() === 'ar' ? $bus->name_ar : $bus->name_en,
                    'total_seats' => $bus->total_seats,
                    'reserved_seats' => $reservedSeats,
                    'available_seats' => $bus->total_seats - count($reservedSeats),
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
        $query = Trip::with(['bus', 'bookedSeats'])
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
                $reservedSeats = $trip->bookedSeats->pluck('seat_number')->toArray();
                
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
                    'total_seats' => $trip->bus->total_seats,
                    'reserved_seats' => $reservedSeats,
                    'available_seats' => $trip->bus->total_seats - count($reservedSeats),
                    'cancellation_policy' => $trip->cancellation_policy,
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
                'cancellation_policy' => $trip->cancellation_policy,
            ],
        ]);
    }

    /**
     * Get available private cars.
     */
    public function getPrivateCars(): JsonResponse
    {
        $cars = PrivateCar::where('is_active', true)
            ->with('media')
            ->get()
            ->map(function ($car) {
                return [
                    'id' => $car->id,
                    'name' => app()->getLocale() === 'ar' ? $car->name_ar : $car->name_en,
                    'car_model' => $car->car_model,
                    'price_per_day' => (float) $car->price_per_day,
                    'price_per_hour' => (float) $car->price_per_hour,
                    'seats_count' => $car->seats_count,
                    'images' => $car->media->map(fn($media) => asset('storage/' . $media->file_path)),
                    'max_speed' => $car->max_speed,
                    'acceleration' => $car->acceleration ? (float) $car->acceleration : null,
                    'power' => $car->power,
                    'fuel_type' => $car->fuel_type ?? 'gasoline',
                    'transmission' => $car->transmission ?? 'automatic',
                    'notes' => app()->getLocale() === 'ar' ? $car->notes_ar : $car->notes_en,
                    'cancellation_policy' => $car->cancellation_policy,
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
                'payment_method' => 'nullable|in:cash,card,bank_transfer,online,other',
                'payment_method_id' => 'nullable|integer',
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
                $closedSeats = ClosedSeat::where('trip_id', $trip->id)->pluck('seat_number')->toArray();
                
                foreach ($validated['selected_seats'] as $seatNumber) {
                    if (in_array($seatNumber, $closedSeats)) {
                        return response()->json([
                            'success' => false,
                            'message' => __('api.services.seat_closed', ['seat' => $seatNumber]),
                        ], 400);
                    }
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

            $paymentData = null;
            if ($request->filled('payment_method')) {
                $paymentData = $this->initiatePayment(
                    $serviceRequest, 
                    $totalPrice, 
                    $request->payment_method, 
                    $request->payment_method_id
                );
            }

            return response()->json([
                'success' => true,
                'message' => __('api.services.request_created'),
                'data' => [
                    'request_id' => $serviceRequest->id,
                    'request_reference' => $serviceRequest->request_reference,
                    'total_price' => $totalPrice,
                    'payment' => $paymentData,
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
                'booking_type' => 'required|in:hours,days',
                'duration' => 'required|integer|min:1',
                'start_date' => 'required|date|after_or_equal:today',
                'start_time' => 'required_if:booking_type,hours|date_format:H:i',
                'notes' => 'nullable|string|max:1000',
                'payment_method' => 'nullable|in:cash,card,bank_transfer,online,other',
                'payment_method_id' => 'nullable|integer',
            ]);

            $car = PrivateCar::findOrFail($validated['private_car_id']);
            
            $durationHours = $validated['booking_type'] === 'days' 
                ? $validated['duration'] * 24 
                : $validated['duration'];
            
            $startDateTime = $validated['start_date'] . ' ' . ($validated['start_time'] ?? '00:00');
            $endDateTime = date('Y-m-d H:i', strtotime($startDateTime . ' +' . $durationHours . ' hours'));

            // Check availability
            $conflict = ServiceRequest::where('private_car_id', $car->id)
                ->whereIn('status', ['pending', 'confirmed', 'in_progress'])
                ->where(function($q) use ($startDateTime, $endDateTime) {
                    $q->where(function($query) use ($startDateTime, $endDateTime) {
                        $query->whereRaw("CONCAT(start_date, ' ', COALESCE(start_time, '00:00:00')) < ?", [$endDateTime])
                              ->whereRaw("DATE_ADD(CONCAT(start_date, ' ', COALESCE(start_time, '00:00:00')), INTERVAL duration_hours HOUR) > ?", [$startDateTime]);
                    });
                })
                ->exists();

            if ($conflict) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.services.car_not_available'),
                ], 400);
            }

            $totalPrice = $validated['booking_type'] === 'days'
                ? $car->price_per_day * $validated['duration']
                : $car->price_per_hour * $validated['duration'];

            $serviceRequest = ServiceRequest::create([
                'user_id' => Auth::id(),
                'service_type' => 'private_car',
                'private_car_id' => $car->id,
                'duration_hours' => $durationHours,
                'start_date' => $validated['start_date'],
                'start_time' => $validated['start_time'] ?? null,
                'booking_type' => $validated['booking_type'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            $paymentData = null;
            if ($request->filled('payment_method')) {
                $paymentData = $this->initiatePayment(
                    $serviceRequest, 
                    $totalPrice, 
                    $request->payment_method, 
                    $request->payment_method_id
                );
            }

            return response()->json([
                'success' => true,
                'message' => __('api.services.request_created'),
                'data' => [
                    'request_id' => $serviceRequest->id,
                    'request_reference' => $serviceRequest->request_reference,
                    'total_price' => $totalPrice,
                    'end_datetime' => $endDateTime,
                    'payment' => $paymentData,
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
    public function getUserRequests(Request $request): JsonResponse
    {
        $query = ServiceRequest::where('user_id', Auth::id())
            ->with(['trip.bus', 'bus', 'privateCar', 'user']);
            ->with(['trip.bus', 'trip.user', 'bus.user', 'privateCar.user', 'user']);

        if ($request->filled('type')) {
            $query->where('service_type', $request->type);
        }

        $requests = $query->orderByDesc('created_at')
            ->get()
            ->map(function ($req) {
            ->map(function ($req) {
                $data = [
                    'id' => $req->id,
                    'request_reference' => $req->request_reference,
                    'service_type' => $req->service_type,
                    'total_price' => (float) $req->total_price,
                    'status' => $req->status,
                    'user' => $req->user,
                    'created_at' => $req->created_at->format('Y-m-d H:i:s'),
                    'id' => $req->id,
                    'request_reference' => $req->request_reference,
                    'service_type' => $req->service_type,
                    'total_price' => (float) $req->total_price,
                    'status' => $req->status,
                    'cancellation_reason' => $req->cancellation_reason,
                    'cancelled_at' => $req->cancelled_at ? $req->cancelled_at->format('Y-m-d H:i:s') : null,
                    'user' => $req->user,
                    'created_at' => $req->created_at->format('Y-m-d H:i:s'),
                ];

                if ($req->service_type === 'bus') {
                    $data['trip'] = $req->trip ? [
                        'departure_location' => app()->getLocale() === 'ar' ? $req->trip->departure_location_ar : $req->trip->departure_location_en,
                        'arrival_location' => app()->getLocale() === 'ar' ? $req->trip->arrival_location_ar : $req->trip->arrival_location_en,
                        'trip_date' => $req->trip->trip_date->format('Y-m-d'),
                if ($req->service_type === 'bus') {
                    $data['trip'] = $req->trip ? [
                        'departure_location' => app()->getLocale() === 'ar' ? $req->trip->departure_location_ar : $req->trip->departure_location_en,
                        'arrival_location' => app()->getLocale() === 'ar' ? $req->trip->arrival_location_ar : $req->trip->arrival_location_en,
                        'trip_date' => $req->trip->trip_date->format('Y-m-d'),
                    ] : null;
                    $data['passengers_count'] = $req->passengers_count;
                    
                    // Owner data for bus
                    $owner = $req->trip && $req->trip->user ? $req->trip->user : ($req->bus && $req->bus->user ? $req->bus->user : null);
                    $data['owner'] = $owner ? [
                        'id' => $owner->id,
                        'name' => $owner->name,
                        'phone' => $owner->phone,
                        'image' => $owner->image,
                    ] : null;
                    $data['passengers_count'] = $req->passengers_count;
                } else {
                    $data['car'] = $req->privateCar ? [
                        'name' => app()->getLocale() === 'ar' ? $req->privateCar->name_ar : $req->privateCar->name_en,
                    $data['car'] = $req->privateCar ? [
                        'name' => app()->getLocale() === 'ar' ? $req->privateCar->name_ar : $req->privateCar->name_en,
                    ] : null;
                    $data['duration_hours'] = $req->duration_hours;
                    $data['start_date'] = $req->start_date ? $req->start_date->format('Y-m-d') : null;

                    // Owner data for car
                    $owner = $req->privateCar ? $req->privateCar->user : null;
                    $data['owner'] = $owner ? [
                        'id' => $owner->id,
                        'name' => $owner->name,
                        'phone' => $owner->phone,
                        'image' => $owner->image,
                    ] : null;
                    $data['duration_hours'] = $req->duration_hours;
                    $data['start_date'] = $req->start_date ? $req->start_date->format('Y-m-d') : null;
                }

                return $data;
            });

        return response()->json([
            'success' => true,
            'data' => $requests,
        ]);
    }

    /**
     * Get unavailable seats for a trip.
     */
    public function getUnavailableSeats(Trip $trip): JsonResponse
    {
        $bookedSeats = BusSeat::where('trip_id', $trip->id)->pluck('seat_number')->toArray();
        $closedSeats = ClosedSeat::where('trip_id', $trip->id)->pluck('seat_number')->toArray();
        
        return response()->json([
            'success' => true,
            'data' => [
                'booked_seats' => $bookedSeats,
                'closed_seats' => $closedSeats,
                'unavailable_seats' => array_values(array_unique(array_merge($bookedSeats, $closedSeats))),
            ],
        ]);
    }

    /**
     * Admin: Toggle seat status (open/close).
     */
    public function toggleSeatStatus(Request $request, Trip $trip): JsonResponse
    {
        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        if ($trip->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'seat_numbers' => 'required|array',
            'seat_numbers.*' => 'integer|min:1',
            'action' => 'required|in:close,open',
        ]);

        foreach ($validated['seat_numbers'] as $seatNumber) {
            if ($validated['action'] === 'close') {
                ClosedSeat::firstOrCreate([
                    'trip_id' => $trip->id,
                    'seat_number' => $seatNumber,
                ]);
            } else {
                ClosedSeat::where('trip_id', $trip->id)
                    ->where('seat_number', $seatNumber)
                    ->delete();
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'تم تحديث حالة المقاعد بنجاح',
            'message' => 'تم تحديث حالة المقاعد بنجاح',
        ]);
    }


}
