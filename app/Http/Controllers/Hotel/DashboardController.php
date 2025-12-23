<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the hotel manager dashboard.
     */
    public function __invoke(): View
    {
        $user = auth()->user();
        $hotels = $user->managedHotels()->withCount('rooms')->get();
        
        $totalRooms = $hotels->sum('rooms_count');
        $totalConversations = \App\Models\Conversation::whereIn('hotel_id', $hotels->pluck('id'))
            ->where('status', 'open')
            ->count();
        
        $unreadMessages = \App\Models\Message::whereHas('conversation', function ($query) use ($hotels) {
            $query->whereIn('hotel_id', $hotels->pluck('id'))
                  ->where('status', 'open');
        })
        ->where('sender_type', 'user')
        ->where('is_read', false)
        ->count();

        return view('hotel.dashboard', compact('hotels', 'totalRooms', 'totalConversations', 'unreadMessages'));
    }
}
