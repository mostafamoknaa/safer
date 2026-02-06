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
            ->with(['province', 'reviews', 'media', 'rooms']);

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

        // Filter by country (New)
        if ($request->filled('country')) {
            $query->where('country', $request->country);
        }

        // Filter by service (Dynamic for names, slugs, or IDs)
        if ($request->filled('service')) {
            $serviceValue = $request->service;
            // Try to find if this is a master service
            $serviceModel = \App\Models\Service::where('name_ar', $serviceValue)
                ->orWhere('name_en', $serviceValue)
                ->first();

            $query->where(function ($q) use ($serviceValue, $serviceModel) {
                // Check if stored as simple string
                $q->whereJsonContains('services', $serviceValue);

                if ($serviceModel) {
                    $q->orWhereJsonContains('services', $serviceModel->name_en)
                        ->orWhereJsonContains('services', $serviceModel->name_ar)
                        ->orWhereJsonContains('services', ['id' => $serviceModel->id])
                        ->orWhereJsonContains('services', ['id' => (string) $serviceModel->id]);
                }
            });
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->withAvg('reviews', 'rating')
                ->having('reviews_avg_rating', '>=', $request->min_rating);
        }

        // Filter by price range (Updated to parse strings like '0-500')
        if ($request->filled('price_range')) {
            $range = explode('-', $request->price_range);
            if (count($range) === 2) {
                $min = (float) $range[0];
                $max = (float) $range[1];
                $query->whereHas('rooms', function ($q) use ($min, $max) {
                    $q->whereBetween('price_per_night', [$min, $max]);
                });
            } elseif (str_contains($request->price_range, '+')) {
                $min = (float) str_replace('+', '', $request->price_range);
                $query->whereHas('rooms', function ($q) use ($min) {
                    $q->where('price_per_night', '>=', $min);
                });
            }
        }

        // Filter by min/max price direct
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

        // Distance Filtering (New)
        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $query->selectRaw(
                "hotels.*, ( 6371 * acos( cos( radians(?) ) *
                cos( radians( lat ) ) *
                cos( radians( lang ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( lat ) ) ) ) AS distance",
                [$lat, $lng, $lat]
            )->having('distance', '<=', 50);
        }

        $hotels = $query->withAvg('reviews', 'rating')
            ->paginate(12)
            ->withQueryString();

        $provinces = Province::all();

        return view('web.hotels.index', compact('hotels', 'provinces'));
    }

    /**
     * Display the specified hotel.
     */
    public function show(Hotel $hotel)
    {
        $today = now()->format('Y-m-d');
        $hotel->load([
            'province',
            'media',
            'reviews.user',
            'rooms' => function ($query) use ($today) {
                $query->where('is_active', true)
                    ->where(function ($q) use ($today) {
                        $q->whereNull('blocked_slots')
                            ->orWhereJsonDoesntContain('blocked_slots', $today);
                    });
            },
            'rooms.media'
        ]);

        // Prepare hotel services for display (Format: [{"id": 1, "image": "..."}, ...])
        $services_list = $hotel->services ?? [];
        $hotelServices = [];

        foreach ($services_list as $s) {
            $serviceName = '';
            $serviceImage = null;

            if (is_array($s)) {
                if (isset($s['id'])) {
                    $model = \App\Models\Service::find($s['id']);
                    if ($model) {
                        $serviceName = app()->getLocale() === 'ar' ? $model->name_ar : $model->name_en;
                        $serviceImage = $model->image; // Prefer database image but fallback to stored image
                    }
                    if (empty($serviceImage)) {
                        $serviceImage = $s['image'] ?? null;
                    }
                } else {
                    // Custom service without ID
                    $serviceName = app()->getLocale() === 'ar' ? ($s['name_ar'] ?? ($s['name_en'] ?? '')) : ($s['name_en'] ?? ($s['name_ar'] ?? ''));
                    $serviceImage = $s['image'] ?? null;
                }
            } else {
                // Backward compatibility for simple strings
                $transKey = "hotel.hotels.services.{$s}";
                $translated = __($transKey);
                $serviceName = ($translated !== $transKey) ? $translated : $s;
            }

            if (!empty($serviceName)) {
                $hotelServices[] = (object) [
                    'name_ar' => $serviceName,
                    'image' => $serviceImage,
                    'icon' => 'fa-check'
                ];
            }
        }

        return view('web.hotels.show', compact('hotel', 'hotelServices'));
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
            ->paginate(12)
            ->withQueryString();

        $title = 'الاماكن الرائجة';
        return view('web.hotels.index_collection', compact('hotels', 'title'));
    }

    /**
     * Display nearby hotels.
     */
    public function nearby(Request $request)
    {
        $query = Hotel::where('is_active', true)
            ->with(['media', 'province', 'rooms']);

        if ($request->filled('lat') && $request->filled('lng')) {
            $lat = $request->lat;
            $lng = $request->lng;
            $query->selectRaw(
                "hotels.*, ( 6371 * acos( cos( radians(?) ) *
                cos( radians( lat ) ) *
                cos( radians( lang ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( lat ) ) ) ) AS distance",
                [$lat, $lng, $lat]
            )->having('distance', '<=', 50)
                ->orderBy('distance');
            $hotels = $query->paginate(12)->withQueryString();
        } else {
            $hotels = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 12, 1, ['path' => $request->url()]);
        }

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
