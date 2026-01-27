<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HotelRoom;
use App\Models\IcalUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IcalController extends Controller
{
    /**
     * Display iCal management for a specific room.
     */
    public function index(HotelRoom $hotelRoom): View
    {
        $hotelRoom->load(['icalUrls', 'hotel']);

        return view('admin.ical.index', compact('hotelRoom'));
    }

    /**
     * Store a new iCal URL.
     */
    public function store(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|url|max:500',
            'name' => 'nullable|string|max:100',
        ]);

        $validated['hotel_room_id'] = $hotelRoom->id;

        IcalUrl::create($validated);

        return redirect()
            ->route('admin.ical.index', $hotelRoom)
            ->with('success', 'تم إضافة رابط التقويم بنجاح');
    }

    /**
     * Remove an iCal URL.
     */
    public function destroy(HotelRoom $hotelRoom, IcalUrl $icalUrl): RedirectResponse
    {
        if ($icalUrl->hotel_room_id !== $hotelRoom->id) {
            abort(403);
        }

        $icalUrl->delete();

        return redirect()
            ->route('admin.ical.index', $hotelRoom)
            ->with('success', 'تم حذف رابط التقويم بنجاح');
    }
}
