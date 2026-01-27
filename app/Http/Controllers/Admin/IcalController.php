<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\IcalUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IcalController extends Controller
{
    /**
     * Display iCal management for a specific hotel.
     */
    public function index(Hotel $hotel): View
    {
        $hotel->load(['icalUrls']);

        return view('admin.ical.index', compact('hotel'));
    }

    /**
     * Store a new iCal URL.
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $validated = $request->validate([
            'url' => 'required|url|max:500',
            'name' => 'nullable|string|max:100',
        ]);

        $validated['hotel_id'] = $hotel->id;

        IcalUrl::create($validated);

        return redirect()
            ->route('admin.ical.index', $hotel)
            ->with('success', 'تم إضافة رابط التقويم بنجاح');
    }

    /**
     * Remove an iCal URL.
     */
    public function destroy(Hotel $hotel, IcalUrl $icalUrl): RedirectResponse
    {
        if ($icalUrl->hotel_id !== $hotel->id) {
            abort(403);
        }

        $icalUrl->delete();

        return redirect()
            ->route('admin.ical.index', $hotel)
            ->with('success', 'تم حذف رابط التقويم بنجاح');
    }
}
