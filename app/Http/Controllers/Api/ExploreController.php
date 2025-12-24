<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Event;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ExploreController extends Controller
{
    /**
     * Get popular places (hotels and events).
     */
    public function getPopularPlaces(): JsonResponse
    {
        $user = auth('sanctum')->user();
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        }

        // Get popular hotels
        $hotels = Hotel::with(['media', 'province', 'reviews'])
            ->where('is_active', true)
            ->whereHas('reviews')
            ->withCount('reviews')
            ->orderByDesc('reviews_count')
            ->take(10)
            ->get()
            ->map(function ($hotel) use ($favoriteIds) {
                $images = $hotel->media->where('type', 'image')->map(function ($media) {
                    return [
                        'url' => $media->file_url,
                        'order' => $media->order_column,
                    ];
                })->sortBy('order')->values();

                return [
                    'id' => $hotel->id,
                    'type' => 'hotel',
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'rating' => $hotel->average_rating ? round($hotel->average_rating, 1) : null,
                    'reviews_count' => $hotel->reviews_count,
                    'is_favorite' => in_array($hotel->id, $favoriteIds),
                    'images' => $images,
                    'province' => $hotel->province ? [
                        'name' => app()->getLocale() === 'ar' ? $hotel->province->name_ar : $hotel->province->name_en,
                    ] : null,
                ];
            });

        // Get popular events
        $events = Event::where('is_active', true)
            ->where('event_date', '>=', now())
            ->orderByDesc('created_at')
            ->take(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'type' => 'event',
                    'name' => app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en,
                    'location' => app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en,
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'price_from' => (float) $event->price,
                    'image' => $event->image_url ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'hotels' => $hotels,
                'events' => $events,
            ],
        ]);
    }

    /**
     * Get nearby places based on location.
     */
    public function getNearbyPlaces(Request $request): JsonResponse
    {
        $request->validate([
            'lat' => 'required|numeric',
            'lng' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:50',
        ]);

        $lat = $request->lat;
        $lng = $request->lng;
        $radius = $request->radius ?? 20;

        $user = auth('sanctum')->user();
        $favoriteIds = [];
        if ($user) {
            $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        }

        // Get nearby hotels
        $hotels = Hotel::with(['media', 'province'])
            ->select('hotels.*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) *
                  cos( radians( lat ) ) *
                  cos( radians( lang ) - radians(?) ) +
                  sin( radians(?) ) *
                  sin( radians( lat ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radius)
            ->where('is_active', true)
            ->whereNotNull('lat')
            ->whereNotNull('lang')
            ->orderBy('distance')
            ->take(10)
            ->get()
            ->map(function ($hotel) use ($favoriteIds) {
                $images = $hotel->media->where('type', 'image')->take(1)->map(function ($media) {
                    return $media->file_url;
                })->values();

                return [
                    'id' => $hotel->id,
                    'type' => 'hotel',
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'distance' => round($hotel->distance, 2) . ' km',
                    'rating' => $hotel->average_rating ? round($hotel->average_rating, 1) : null,
                    'is_favorite' => in_array($hotel->id, $favoriteIds),
                    'images' => $images,
                ];
            });

        // Get nearby events
        $events = Event::select('events.*')
            ->selectRaw(
                '( 6371 * acos( cos( radians(?) ) *
                  cos( radians( lat ) ) *
                  cos( radians( lng ) - radians(?) ) +
                  sin( radians(?) ) *
                  sin( radians( lat ) ) ) ) AS distance',
                [$lat, $lng, $lat]
            )
            ->having('distance', '<=', $radius)
            ->where('is_active', true)
            ->where('event_date', '>=', now())
            ->whereNotNull('lat')
            ->whereNotNull('lng')
            ->orderBy('distance')
            ->take(10)
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'type' => 'event',
                    'name' => app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en,
                    'location' => app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en,
                    'distance' => round($event->distance, 2) . ' km',
                    'event_date' => $event->event_date->format('Y-m-d'),
                    'price_from' => (float) $event->price,
                    'image' => $event->image_url ?? null,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'hotels' => $hotels,
                'events' => $events,
            ],
        ]);
    }
}