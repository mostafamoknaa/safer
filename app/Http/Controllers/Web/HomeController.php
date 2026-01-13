<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Event;
use App\Models\Province;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Display the homepage.
     */
    public function index()
    {
        // Get provinces for filter
        $provinces = Province::where('is_active', true)->orderBy('name_ar')->get();

        // Get unique services from hotels
        $services = Hotel::whereNotNull('services')
            ->get()
            ->pluck('services')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();

        // Get popular hotels (top rated)
        $popularHotels = Hotel::where('is_active', true)
            ->with(['media', 'province', 'rooms'])
            ->orderBy('rate', 'desc')
            ->take(6)
            ->get();

        // Get nearby/recent hotels
        $nearbyHotels = Hotel::where('is_active', true)
            ->with(['media', 'province', 'rooms'])
            ->latest()
            ->take(4)
            ->get();

        // Get upcoming events (for discovery section)
        $events = Event::where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->take(5)
            ->get();

        return view('web.home', compact('popularHotels', 'nearbyHotels', 'events', 'provinces', 'services'));
    }
}
