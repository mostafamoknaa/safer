<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\Payment;
use App\Models\ServiceRequest;
use App\Models\Event;
use App\Models\EventTicket;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Response as ResponseFactory;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Bookings report (table + CSV export).
     */
    public function bookings(Request $request)
    {
        $query = Booking::with(['user', 'hotel', 'room'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
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
                'Content-Disposition' => 'attachment; filename="bookings_report.csv"',
            ];

            $callback = function () use ($bookings) {
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

        $hotels = Hotel::orderBy('name_ar')->get();
        $users = User::where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.reports.bookings', compact('bookings', 'hotels', 'users'));
    }

    /**
     * Payments report.
     */
    public function payments(Request $request)
    {
        $query = Payment::with(['booking.user', 'booking.hotel'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('method')) {
            $query->where('payment_method', $request->method);
        }

        if ($request->filled('hotel_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('hotel_id', $request->hotel_id);
            });
        }

        if ($request->filled('user_id')) {
            $query->whereHas('booking', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
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
                'Content-Disposition' => 'attachment; filename="payments_report.csv"',
            ];

            $callback = function () use ($payments) {
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

        $hotels = Hotel::orderBy('name_ar')->get();
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.reports.payments', compact('payments', 'hotels', 'users'));
    }

    /**
     * Service requests report.
     */
    public function services(Request $request)
    {
        $query = ServiceRequest::with(['user', 'trip.bus', 'privateCar'])
            ->orderByDesc('created_at');

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // CSV export
        if ($request->query('export') === 'csv') {
            $requests = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="service_requests_report.csv"',
            ];

            $callback = function () use ($requests) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($handle, [
                    'ID',
                    'Reference',
                    'User',
                    'Type',
                    'Details',
                    'Total Price',
                    'Status',
                    'Created At',
                ]);

                foreach ($requests as $req) {
                    $details = '';
                    if ($req->service_type === 'bus') {
                        $details = ($req->departure_location_ar ?? $req->departure_location_en)
                            . ' -> '
                            . ($req->arrival_location_ar ?? $req->arrival_location_en);
                    } else {
                        $details = $req->privateCar
                            ? (app()->getLocale() === 'ar' ? $req->privateCar->name_ar : $req->privateCar->name_en)
                            : '';
                    }

                    fputcsv($handle, [
                        $req->id,
                        $req->request_reference,
                        $req->user?->name,
                        $req->service_type,
                        $details,
                        $req->total_price,
                        $req->status,
                        optional($req->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return ResponseFactory::stream($callback, 200, $headers);
        }

        $requests = $query->paginate(20)->withQueryString();
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.reports.services', compact('requests', 'users'));
    }

    /**
     * Events & tickets report.
     */
    public function events(Request $request)
    {
        $query = EventTicket::with(['event', 'user'])
            ->orderByDesc('created_at');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_id')) {
            $query->where('event_id', $request->event_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('created_at', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('created_at', '<=', $request->to_date);
        }

        // CSV export
        if ($request->query('export') === 'csv') {
            $tickets = $query->get();

            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => 'attachment; filename="events_tickets_report.csv"',
            ];

            $callback = function () use ($tickets) {
                $handle = fopen('php://output', 'w');
                fwrite($handle, chr(0xEF) . chr(0xBB) . chr(0xBF));

                fputcsv($handle, [
                    'ID',
                    'Ticket Reference',
                    'Event',
                    'User',
                    'Tickets Count',
                    'Total Price',
                    'Status',
                    'Event Date',
                    'Created At',
                ]);

                foreach ($tickets as $ticket) {
                    fputcsv($handle, [
                        $ticket->id,
                        $ticket->ticket_reference,
                        $ticket->event ? (app()->getLocale() === 'ar' ? $ticket->event->name_ar : $ticket->event->name_en) : null,
                        $ticket->user?->name,
                        $ticket->tickets_count,
                        $ticket->total_price,
                        $ticket->status,
                        $ticket->event && $ticket->event->event_date ? $ticket->event->event_date->format('Y-m-d H:i:s') : null,
                        optional($ticket->created_at)->format('Y-m-d H:i:s'),
                    ]);
                }

                fclose($handle);
            };

            return ResponseFactory::stream($callback, 200, $headers);
        }

        $tickets = $query->paginate(20)->withQueryString();
        $events = Event::orderBy('event_date', 'desc')->get();
        $users = User::where('is_admin', false)->orderBy('name')->get();

        return view('admin.reports.events', compact('tickets', 'events', 'users'));
    }
}


