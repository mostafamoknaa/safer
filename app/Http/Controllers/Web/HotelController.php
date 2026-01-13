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
            ->with(['province', 'ratings']);

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
            $query->withAvg('ratings', 'rating')
                ->having('ratings_avg_rating', '>=', $request->min_rating);
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

        $hotels = $query->withAvg('ratings', 'rating')
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
}
