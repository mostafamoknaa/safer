<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HotelRoom;
use App\Models\Payment;
use App\Models\User;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function __invoke(): View
    {
        $today = now()->toDateString();

        // إجمالي الحجوزات
        $totalBookings = Booking::count();

        // نسبة الإشغال اليوم
        $totalRooms = HotelRoom::where('is_active', true)->count();
        $occupiedRoomsToday = 0;
        $occupancyRate = 0;

        if ($totalRooms > 0) {
            $occupiedRoomsToday = Booking::where('status', '!=', 'cancelled')
                ->whereDate('check_in_date', '<=', $today)
                ->whereDate('check_out_date', '>', $today)
                ->distinct('room_id')
                ->count('room_id');

            $occupancyRate = round(($occupiedRoomsToday / $totalRooms) * 100);
        }

        // عملاء جدد آخر 30 يوم
        $newClientsLast30 = User::where('is_admin', false)
            ->whereDate('created_at', '>=', now()->subDays(30))
            ->count();

        // إجمالي الإيرادات (مدفوعات مكتملة)
        $totalRevenue = Payment::where('status', 'completed')->sum('amount');

        return view('admin.dashboard', [
            'totalBookings' => $totalBookings,
            'occupancyRate' => $occupancyRate,
            'newClientsLast30' => $newClientsLast30,
            'totalRevenue' => $totalRevenue,
        ]);
    }
}

