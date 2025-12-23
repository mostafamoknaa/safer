<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Bookings report for hotel managers (table + CSV export).
     */
    public function bookings(Request $request)
    {
        $manager = auth()->user();
        $hotelIds = $manager->managedHotels()
            ->select('hotels.id')
            ->pluck('hotels.id');

        $query = Booking::with(['user', 'hotel', 'room'])
            ->whereIn('hotel_id', $hotelIds)
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // CSV export
        if ($request->query('export') === 'csv') {
            $bookings = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="hotel_bookings_report.csv"',
            ];

            $callback = static function () use ($bookings) {
                $handle = fopen('php://output', 'w');

                // BOM for UTF-8 Excel support
                fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($handle, [
                    'ID',
                    'Reference',
                    'User',
                    'Hotel',
                    'Room',
                    'Status',
                    'Check-in',
                    'Check-out',
                    'Nights',
                    'Rooms Count',
                    'Guests Count',
                    'Total Price',
                    'Created At',
                ]);

                foreach ($bookings as $booking) {
                    fputcsv($handle, [
                        $booking->id,
                        $booking->booking_reference,
                        $booking->user?->name,
                        $booking->hotel ? (app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en) : null,
                        $booking->room_id,
                        $booking->status,
                        optional($booking->check_in_date)->format('Y-m-d'),
                        optional($booking->check_out_date)->format('Y-m-d'),
                        $booking->nights_count,
                        $booking->rooms_count,
                        $booking->guests_count,
                        $booking->total_price,
                        optional($booking->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return ResponseFactory::stream($callback, 200, $headers);
        }

        $bookings = $query->paginate(20)->withQueryString();

        $hotels = Hotel::whereIn('id', $hotelIds)
            ->orderBy('name_ar')
            ->get();

        return view('hotel.reports.bookings', compact('bookings', 'hotels'));
    }

    /**
     * Payments report for hotel managers.
     */
    public function payments(Request $request)
    {
        $manager = auth()->user();
        $hotelIds = $manager->managedHotels()
            ->select('hotels.id')
            ->pluck('hotels.id');

        $query = Payment::with(['booking.user', 'booking.hotel'])
            ->whereHas('booking', static function ($q) use ($hotelIds) {
                $q->whereIn('hotel_id', $hotelIds);
            })
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('hotel_id')) {
            $query->whereHas('booking', static function ($q) use ($request) {
                $q->where('hotel_id', $request->hotel_id);
            });
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // CSV export
        if ($request->query('export') === 'csv') {
            $payments = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="hotel_payments_report.csv"',
            ];

            $callback = static function () use ($payments) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($handle, [
                    'ID',
                    'Booking Reference',
                    'User',
                    'Hotel',
                    'Amount',
                    'Method',
                    'Status',
                    'Paid At',
                    'Created At',
                ]);

                foreach ($payments as $payment) {
                    fputcsv($handle, [
                        $payment->id,
                        $payment->booking?->booking_reference,
                        $payment->booking?->user?->name,
                        $payment->booking?->hotel
                            ? (app()->getLocale() === 'ar'
                                ? $payment->booking->hotel->name_ar
                                : $payment->booking->hotel->name_en)
                            : null,
                        $payment->amount,
                        $payment->payment_method,
                        $payment->status,
                        optional($payment->paid_at)->format('Y-m-d H:i:s'),
                        optional($payment->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return ResponseFactory::stream($callback, 200, $headers);
        }

        $payments = $query->paginate(20)->withQueryString();

        $hotels = Hotel::whereIn('id', $hotelIds)
            ->orderBy('name_ar')
            ->get();

        return view('hotel.reports.payments', compact('payments', 'hotels'));
    }
}


