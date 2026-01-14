<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Favorite;
use App\Models\Hotel;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the user's favorites.
     */
    public function index()
    {
        $favorites = auth()->user()->favorites()->with('hotel.province', 'hotel.media', 'hotel.rooms')->get();
        return view('web.favorites.index', compact('favorites'));
    }

    /**
     * Toggle a favorite for a hotel.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
        ]);

        $user = auth()->user();
        $hotelId = $request->hotel_id;

        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $hotelId)
            ->first();

        if ($favorite) {
            $favorite->delete();
            return response()->json([
                'status' => 'removed',
                'message' => 'تم الإزالة من المفضلة'
            ]);
        } else {
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $hotelId,
            ]);
            return response()->json([
                'status' => 'added',
                'message' => 'تم الإضافة إلى المفضلة'
            ]);
        }
    }
}
