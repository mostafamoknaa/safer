<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use App\Models\Trip;
use App\Models\PrivateCar;
use App\Models\ServiceRequest;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    // Buses Management
    public function buses()
    {
        $buses = Bus::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.services.buses.index', compact('buses'));
    }

    public function createBus()
    {
        return view('admin.services.buses.create');
    }

    public function storeBus(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1|max:100',
            'type' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        Bus::create($request->all());
        return redirect()->route('admin.services.buses')->with('success', 'تم إنشاء الحافلة بنجاح');
    }

    public function editBus(Bus $bus)
    {
        return view('admin.services.buses.edit', compact('bus'));
    }

    public function updateBus(Request $request, Bus $bus)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1|max:100',
            'type' => 'required|string|max:100',
            'is_active' => 'boolean',
        ]);

        $bus->update($request->all());
        return redirect()->route('admin.services.buses')->with('success', 'تم تحديث الحافلة بنجاح');
    }

    public function destroyBus(Bus $bus)
    {
        $bus->delete();
        return redirect()->route('admin.services.buses')->with('success', 'تم حذف الحافلة بنجاح');
    }

    // Trips Management
    public function trips()
    {
        $trips = Trip::with('bus')->orderBy('trip_date', 'desc')->paginate(15);
        return view('admin.services.trips.index', compact('trips'));
    }

    public function createTrip()
    {
        $buses = Bus::where('is_active', true)->get();
        return view('admin.services.trips.create', compact('buses'));
    }

    public function storeTrip(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'trip_date' => 'required|date',
            'trip_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        Trip::create($request->all());
        return redirect()->route('admin.services.trips')->with('success', 'تم إنشاء الرحلة بنجاح');
    }

    public function editTrip(Trip $trip)
    {
        $buses = Bus::where('is_active', true)->get();
        return view('admin.services.trips.edit', compact('trip', 'buses'));
    }

    public function updateTrip(Request $request, Trip $trip)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'departure_location_ar' => 'required|string|max:255',
            'departure_location_en' => 'required|string|max:255',
            'arrival_location_ar' => 'required|string|max:255',
            'arrival_location_en' => 'required|string|max:255',
            'trip_date' => 'required|date',
            'trip_time' => 'required|date_format:H:i',
            'price' => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:1',
            'is_active' => 'boolean',
        ]);

        $trip->update($request->all());
        return redirect()->route('admin.services.trips')->with('success', 'تم تحديث الرحلة بنجاح');
    }

    public function destroyTrip(Trip $trip)
    {
        $trip->delete();
        return redirect()->route('admin.services.trips')->with('success', 'تم حذف الرحلة بنجاح');
    }

    // Private Cars Management
    public function privateCars()
    {
        $cars = PrivateCar::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.services.private-cars.index', compact('cars'));
    }

    public function createPrivateCar()
    {
        return view('admin.services.private-cars.create');
    }

    public function storePrivateCar(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'seats_count' => 'required|integer|min:1|max:20',
            'max_speed' => 'nullable|integer|min:1',
            'acceleration' => 'nullable|numeric|min:0',
            'power' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        PrivateCar::create($request->all());
        return redirect()->route('admin.services.private-cars')->with('success', 'تم إنشاء السيارة بنجاح');
    }

    public function editPrivateCar(PrivateCar $privateCar)
    {
        return view('admin.services.private-cars.edit', compact('privateCar'));
    }

    public function updatePrivateCar(Request $request, PrivateCar $privateCar)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'seats_count' => 'required|integer|min:1|max:20',
            'max_speed' => 'nullable|integer|min:1',
            'acceleration' => 'nullable|numeric|min:0',
            'power' => 'nullable|string|max:100',
            'fuel_type' => 'nullable|string|max:50',
            'transmission' => 'nullable|string|max:50',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'image_url' => 'nullable|url',
            'is_active' => 'boolean',
        ]);

        $privateCar->update($request->all());
        return redirect()->route('admin.services.private-cars')->with('success', 'تم تحديث السيارة بنجاح');
    }

    public function destroyPrivateCar(PrivateCar $privateCar)
    {
        $privateCar->delete();
        return redirect()->route('admin.services.private-cars')->with('success', 'تم حذف السيارة بنجاح');
    }

    // Service Requests Management
    public function requests()
    {
        $requests = ServiceRequest::with(['user', 'trip', 'bus', 'privateCar'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('admin.services.requests.index', compact('requests'));
    }

    public function showRequest(ServiceRequest $request)
    {
        $request->load(['user', 'trip', 'bus', 'privateCar']);
        return view('admin.services.requests.show', compact('request'));
    }

    public function updateRequestStatus(Request $request, ServiceRequest $serviceRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed',
        ]);

        $serviceRequest->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }
}