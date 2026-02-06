<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceRequest;
use App\Models\Trip;
use App\Models\Bus;
use App\Models\PrivateCar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRequestController extends Controller
{
    /**
     * Display a listing of service requests.
     */
    public function index(Request $request): View
    {
        $query = ServiceRequest::with(['user', 'trip.bus', 'bus', 'privateCar'])
            ->orderByDesc('created_at');

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('request_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $requests = $query->paginate(15)->withQueryString();

        return view('admin.service-requests.index', compact('requests'));
    }

    /**
     * Display the specified service request.
     */
    public function show(ServiceRequest $serviceRequest): View
    {
        $serviceRequest->load(['user', 'trip.bus', 'bus', 'privateCar', 'bookedSeats']);

        return view('admin.service-requests.show', compact('serviceRequest'));
    }

    /**
     * Show the form for editing the specified service request.
     */
    public function edit(ServiceRequest $serviceRequest): View
    {
        $serviceRequest->load(['trip', 'bus', 'privateCar']);

        $trips = collect();
        $buses = collect();
        $cars = collect();

        if ($serviceRequest->service_type === 'bus_trip' || $serviceRequest->service_type === 'bus') {
            $trips = Trip::where('is_active', true)->with('bus')->orderBy('trip_date')->get();
            $buses = Bus::where('is_active', true)->orderBy('name_ar')->get();
        } else {
            $cars = PrivateCar::where('is_active', true)->orderBy('name_ar')->get();
        }

        return view('admin.service-requests.edit', compact('serviceRequest', 'trips', 'buses', 'cars'));
    }

    /**
     * Update the specified service request.
     */
    public function update(Request $request, ServiceRequest $serviceRequest): RedirectResponse
    {
        $rules = [
            'status' => 'required|in:pending,confirmed,in_progress,completed,cancelled',
            'notes' => 'nullable|string|max:1000',
        ];

        if ($serviceRequest->service_type === 'bus_trip' || $serviceRequest->service_type === 'bus') {
            $rules = array_merge($rules, [
                'trip_id' => 'required|exists:trips,id',
                'bus_id' => 'required|exists:buses,id',
            ]);
        } else {
            $rules = array_merge($rules, [
                'private_car_id' => 'required|exists:private_cars,id',
            ]);
        }

        $validated = $request->validate($rules);

        $serviceRequest->update([
            'status' => $validated['status'],
            'notes' => $validated['notes'] ?? $serviceRequest->notes,
        ]);

        return redirect()
            ->route('admin.service-requests.show', $serviceRequest)
            ->with('success', __('admin.service_requests.messages.updated'));
    }

    /**
     * Remove the specified service request.
     */
    public function destroy(ServiceRequest $serviceRequest): RedirectResponse
    {
        $serviceRequest->delete();

        return redirect()
            ->route('admin.service-requests.index')
            ->with('success', __('admin.service_requests.messages.deleted'));
    }
}
