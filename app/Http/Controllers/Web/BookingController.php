<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\HotelRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    /**
     * Display user's bookings.
     */
    public function index()
    {
        $bookings = auth()->user()->bookings()
            ->with(['room.hotel', 'payments'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'bookings_page');

        $requests = auth()->user()->serviceRequests()
            ->with(['trip.bus', 'privateCar'])
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'requests_page');

        $eventTickets = auth()->user()->eventTickets()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'events_page');

        return view('web.bookings.index', compact('bookings', 'requests', 'eventTickets'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:hotel_rooms,id',
            'check_in' => 'required|date|after:today',
            'check_out' => 'required|date|after:check_in',
        ]);

        $room = HotelRoom::with('hotel')->findOrFail($request->room_id);

        // Calculate number of nights
        $checkIn = \Carbon\Carbon::parse($request->check_in);
        $checkOut = \Carbon\Carbon::parse($request->check_out);
        $nights = $checkIn->diffInDays($checkOut);

        // Calculate total price
        $totalPrice = $room->price_per_night * $nights;

        return view('web.bookings.create', compact('room', 'checkIn', 'checkOut', 'nights', 'totalPrice'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'hotel_id' => 'required|exists:hotels,id',
            'rooms' => 'required|array',
            'rooms.*' => 'integer|min:0',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);

        $totalPrice = 0;
        $totalRoomsCount = 0;
        $bookedRoomsData = [];

        foreach ($validated['rooms'] as $roomId => $count) {
            if ($count > 0) {
                $room = HotelRoom::findOrFail($roomId);

                // Optional: Check if room belongs to the hotel
                if ($room->hotel_id != $validated['hotel_id']) {
                    continue;
                }

                $roomTotal = ($room->price_per_night * $count * $nights) +
                    ($room->cleaning_fee * $count) +
                    ($room->service_fee * $count);

                $totalPrice += $roomTotal;
                $totalRoomsCount += $count;
                $bookedRoomsData[] = [
                    'room_id' => $roomId,
                    'count' => $count,
                    'price' => $room->price_per_night,
                ];
            }
        }

        if ($totalRoomsCount === 0) {
            return back()->withErrors(['rooms' => 'يرجى اختيار غرفة واحدة على الأقل']);
        }

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'hotel_id' => $validated['hotel_id'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'guests_count' => $validated['number_of_guests'],
                'rooms_count' => $totalRoomsCount,
                'price_per_night' => !empty($bookedRoomsData) ? $bookedRoomsData[0]['price'] : 0,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['special_requests'] ?? null,
                'nights_count' => $nights,
            ]);

            // Save individual rooms
            foreach ($bookedRoomsData as $roomData) {
                \App\Models\BookingRoom::create([
                    'booking_id' => $booking->id,
                    'room_id' => $roomData['room_id'],
                    'rooms_count' => $roomData['count'],
                ]);
            }

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'تم إنشاء الحجز بنجاح',
                    'redirect' => route('web.bookings.show', $booking)
                ]);
            }

            return redirect()->route('web.bookings.show', $booking)
                ->with('success', 'تم إنشاء الحجز بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage()
                ], 500);
            }
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحجز: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking)
    {
        // Ensure user can only view their own bookings
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        $booking->load(['room.hotel', 'payments']);

        return view('web.bookings.show', compact('booking'));
    }

    /**
     * Cancel the specified booking.
     */
    public function cancel(Booking $booking)
    {
        // Ensure user can only cancel their own bookings
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        if ($booking->status === 'cancelled') {
            return back()->withErrors(['error' => 'الحجز ملغى بالفعل']);
        }

        if ($booking->status === 'completed') {
            return back()->withErrors(['error' => 'لا يمكن إلغاء حجز مكتمل']);
        }

        $booking->update(['status' => 'cancelled']);

        return back()->with('success', 'تم إلغاء الحجز بنجاح');
    }

    public function serviceShow($id)
    {
        $request = \App\Models\ServiceRequest::with(['trip.bus', 'privateCar', 'bookedSeats'])->findOrFail($id);

        if ($request->user_id !== auth()->id()) {
            abort(403);
        }

        return view('web.bookings.service_show', compact('request'));
    }

    public function eventTicketShow($id)
    {
        $ticket = \App\Models\EventTicket::with('event')->findOrFail($id);

        if ($ticket->user_id !== auth()->id()) {
            abort(403);
        }

        return view('web.bookings.event_show', compact('ticket'));
    }
}
