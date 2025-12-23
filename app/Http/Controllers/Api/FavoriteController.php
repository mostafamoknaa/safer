<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function getFavorites(Request $request)
    {
        $favorites = Favorite::where('user_id', $request->user()->id)
            ->with('hotel.province', 'hotel.rooms', 'hotel.media', 'hotel.managers', 'hotel.conversations', 'hotel.bookings')
            ->get();

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
        ]);
    
        return response()->json([
            'success'      => true,
            'message'      => 'Added to favorites',
            'data'         => $favorite->load('favoritable'),
        ]);
    }
    
}