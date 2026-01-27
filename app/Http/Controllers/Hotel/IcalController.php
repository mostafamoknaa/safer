<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\IcalUrl;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;

class IcalController extends Controller
{
    /**
     * Display iCal management for a specific hotel.
     */
    public function index(Request $request, Hotel $hotel): View
    {
        $user = auth()->user();
        
        if (!$user->managesHotel($hotel->id)) {
            abort(403, 'You do not have access to this hotel.');
        }

        $hotel->load(['icalUrls']);

        return view('hotel.ical.index', compact('hotel'));
    }

    /**
     * Store a new iCal URL.
     */
    public function store(Request $request, Hotel $hotel): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user->managesHotel($hotel->id)) {
            abort(403);
        }

        $validated = $request->validate([
            'url' => 'required|url|max:500',
            'name' => 'nullable|string|max:100',
        ]);

        $validated['hotel_id'] = $hotel->id;

        IcalUrl::create($validated);

        return redirect()
            ->route('hotel.ical.index', $hotel)
            ->with('success', 'تم إضافة رابط التقويم بنجاح');
    }

    /**
     * Remove an iCal URL.
     */
    public function destroy(Hotel $hotel, IcalUrl $icalUrl): RedirectResponse
    {
        $user = auth()->user();
        
        if (!$user->managesHotel($hotel->id) || $icalUrl->hotel_id !== $hotel->id) {
            abort(403);
        }

        $icalUrl->delete();

        return redirect()
            ->route('hotel.ical.index', $hotel)
            ->with('success', 'تم حذف رابط التقويم بنجاح');
    }

    /**
     * Export iCal feed for a hotel.
     */
    public function export(Hotel $hotel): Response
    {
        $bookings = $hotel->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->get();

        $ical = $this->generateIcal($hotel, $bookings);

        return response($ical, 200)
            ->header('Content-Type', 'text/calendar; charset=utf-8')
            ->header('Content-Disposition', 'attachment; filename="hotel-' . $hotel->id . '.ics"');
    }

    /**
     * Generate iCal content.
     */
    private function generateIcal(Hotel $hotel, $bookings): string
    {
        $ical = "BEGIN:VCALENDAR\r\n";
        $ical .= "VERSION:2.0\r\n";
        $ical .= "PRODID:-//SaferPlus//Hotel Booking System//EN\r\n";
        $ical .= "CALSCALE:GREGORIAN\r\n";
        $ical .= "METHOD:PUBLISH\r\n";
        $ical .= "X-WR-CALNAME:" . ($hotel->name_en ?: $hotel->name_ar) . "\r\n";
        $ical .= "X-WR-TIMEZONE:UTC\r\n";

        foreach ($bookings as $booking) {
            $uid = $booking->external_event_uid ?: 'booking-' . $booking->id . '@saferplus.net';
            $summary = 'Booking: ' . $booking->booking_reference;
            $description = 'Guest: ' . ($booking->user->name ?? 'N/A');
            if ($booking->room) {
                $description .= " | Room: " . $booking->room->name;
            }
            
            $dtstart = $booking->check_in_date->format('Ymd');
            $dtend = $booking->check_out_date->format('Ymd');
            $dtstamp = $booking->created_at->format('Ymd\THis\Z');

            $ical .= "BEGIN:VEVENT\r\n";
            $ical .= "UID:$uid\r\n";
            $ical .= "DTSTAMP:$dtstamp\r\n";
            $ical .= "DTSTART;VALUE=DATE:$dtstart\r\n";
            $ical .= "DTEND;VALUE=DATE:$dtend\r\n";
            $ical .= "SUMMARY:$summary\r\n";
            $ical .= "DESCRIPTION:$description\r\n";
            $ical .= "STATUS:CONFIRMED\r\n";
            $ical .= "TRANSP:OPAQUE\r\n";
            $ical .= "END:VEVENT\r\n";
        }

        $ical .= "END:VCALENDAR\r\n";

        return $ical;
    }
}
