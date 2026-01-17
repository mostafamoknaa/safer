<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Bus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BusController extends Controller
{
    public function index(): View
    {
        $buses = Bus::where('user_id', auth()->id())
            ->withCount('trips')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('hotel.buses.index', compact('buses'));
    }

    public function create(): View
    {
        return view('hotel.buses.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active');

        Bus::create($data);

        return redirect()->route('hotel.buses.index')->with('success', 'Bus created successfully');
    }

    public function edit(Bus $bus): View
    {
        if ($bus->user_id !== auth()->id()) {
            abort(403);
        }

        return view('hotel.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus): RedirectResponse
    {
        if ($bus->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'total_seats' => 'required|integer|min:1',
            'type' => 'required|string|max:255',
            'is_active' => 'sometimes|boolean',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $bus->update($data);

        return redirect()->route('hotel.buses.index')->with('success', 'Bus updated successfully');
    }

    public function destroy(Bus $bus): RedirectResponse
    {
        if ($bus->user_id !== auth()->id()) {
            abort(403);
        }

        if ($bus->trips()->count() > 0) {
            return redirect()->route('hotel.buses.index')->with('error', 'Cannot delete bus with trips');
        }

        $bus->delete();

        return redirect()->route('hotel.buses.index')->with('success', 'Bus deleted successfully');
    }
}
