<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Province;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    /**
     * Display a listing of hotels.
     */
    public function index(Request $request)
    {
        $query = Hotel::where('is_active', true)
            ->with(['province', 'reviews']);

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        // Filter by province
        if ($request->filled('province_id')) {
            $query->where('province_id', $request->province_id);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        // Filter by price range
        if ($request->filled('min_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('price_per_night', '>=', $request->min_price);
            });
        }

        if ($request->filled('max_price')) {
            $query->whereHas('rooms', function ($q) use ($request) {
                $q->where('price_per_night', '<=', $request->max_price);
            });
        }

        $hotels = $query->withAvg('reviews', 'rating')
            ->paginate(12);

        $provinces = Province::all();

        return view('web.hotels.index', compact('hotels', 'provinces'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        $hotel->load([
            'province',
            'media',
            'reviews.user',
            'rooms' => function ($query) {
                $query->where('is_active', true);
            },
            'rooms.media'
        ]);

        return view('web.hotels.show', compact('hotel'));
    }

    /**
     * Display hotel rooms.
     */
    public function rooms(Hotel $hotel, Request $request)
    {
        $query = $hotel->rooms()->where('is_available', true);

        // Filter by capacity
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter by price
        if ($request->filled('max_price')) {
            $query->where('price_per_night', '<=', $request->max_price);
        }

        $rooms = $query->get();

        return view('web.hotels.rooms', compact('hotel', 'rooms'));
    }

    /**
     * Display popular hotels.
     */
    public function popular()
    {
        $hotels = Hotel::where('is_active', true)
            ->with(['media', 'province', 'rooms'])
            ->orderBy('rate', 'desc')
            ->paginate(12);

        $title = 'الاماكن الرائجة';
        return view('web.hotels.index_collection', compact('hotels', 'title'));
    }

    /**
     * Display nearby hotels.
     */
    public function nearby()
    {
        $hotels = Hotel::where('is_active', true)
            ->with(['media', 'province', 'rooms'])
            ->latest()
            ->paginate(12);

        $title = 'الاماكن القريبة';
        return view('web.hotels.index_collection', compact('hotels', 'title'));
    }

    /**
     * Display discovery hotels (now Events).
     */
    public function discovery()
    {
        $events = \App\Models\Event::where('is_active', true)
            ->orderBy('event_date', 'desc')
            ->paginate(12);

        $title = 'اكتشاف اماكن';
        return view('web.events.discovery', compact('events', 'title'));
    }
}
