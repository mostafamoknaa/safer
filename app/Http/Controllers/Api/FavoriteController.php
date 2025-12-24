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
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->with('hotel.province', 'hotel.rooms', 'hotel.media', 'hotel.managers', 'hotel.conversations', 'hotel.bookings')
            ->get()
            ->map(function ($favorite) {
                $hotel = $favorite->hotel;
                if (!$hotel) return null;

                $images = $hotel->media->where('type', 'image')->map(function ($media) {
                    return [
                        'url' => $media->file_url,
                        'order' => $media->order_column,
                    ];
                })->sortBy('order')->values();

                return [
                    'id' => $favorite->id,
                    'hotel' => [
                        'id' => $hotel->id,
                        'name' => app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en,
                        'address' => app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en,
                        'rating' => $hotel->average_rating ? round($hotel->average_rating, 1) : null,
                        'images' => $images,
                        'province' => $hotel->province ? [
                            'name' => app()->getLocale() === 'ar' ? $hotel->province->name_ar : $hotel->province->name_en,
                        ] : null,
                    ],
                    'created_at' => $favorite->created_at->format('Y-m-d H:i:s'),
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