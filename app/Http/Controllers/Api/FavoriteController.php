<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Support\Facades\App;
use App\Models\Hotel;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function getFavorites(Request $request)
    {
        $user = $request->user();
        $favoriteIds = Favorite::where('user_id', $user->id)->pluck('favoritable_id')->toArray();
        
        $favorites = Favorite::where('user_id', $user->id)
            ->with([
                'hotel.media',
                'hotel.province',
                'hotel.rooms' => function ($q) {
                    $q->where('is_active', true);
                },
                'hotel.reviews.user'
            ])
            ->get()
            ->map(function ($favorite) use ($favoriteIds) {
                $hotel = $favorite->hotel;
                if (!$hotel) return null;

                $minPrice = $hotel->rooms->min('price_per_night');
                $maxPrice = $hotel->rooms->max('price_per_night');

                $images = $hotel->media->where('type', 'image')->map(function ($media) {
                    return [
                        'url' => $media->file_url,
                        'order' => $media->order_column,
                    ];
                })->sortBy('order')->values();

                return [
                    // 🔹 all hotel columns
                    ...$hotel->toArray(),

                    // 🔹 localized fields
                    'is_favorite' => true, // Always true for favorites
                    'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                    'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                    'about_info' => app()->getLocale() === 'ar'
                        ? $hotel->about_info_ar
                        : $hotel->about_info_en,

                    // 🔹 extra computed data
                    'rooms_count' => $hotel->rooms->count(),
                    'images' => $images,
                    'average_rating' => $hotel->average_rating ? round($hotel->average_rating, 1) : null,
                    'reviews_count' => $hotel->reviews_count,
                    'price' => ($minPrice !== null && $maxPrice !== null)
                        ? [
                            'min' => (float) $minPrice,
                            'max' => (float) $maxPrice,
                        ]
                        : null,
                ];
            })
            ->filter()
            ->values();

        return response()->json([
            'success' => true,
            'data' => $favorites
        ]);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'hotel_id'     => 'required|exists:hotels,id',
        ]);
    
        $user = $request->user();
    
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $request->hotel_id)
            ->where('favoritable_type', Hotel::class)
            ->first();
    
        if ($favorite) {
            $favorite->delete();
    
            return response()->json([
                'success'      => true,
                'message'      => 'Removed from favorites',
                'is_favorite'  => false,
            ]);
        }
    
        $favorite = Favorite::create([
            'user_id'          => $user->id,
            'favoritable_id'   => $request->hotel_id,
            'favoritable_type' => Hotel::class,
        ]);
    
        return response()->json([
            'success'      => true,
            'message'      => 'Added to favorites',
            'data'         => $favorite->load('favoritable'),
        ]);
    }
    
}