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
            ->paginate(10);

        return view('web.bookings.index', compact('bookings'));
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
            'room_id' => 'required|exists:hotel_rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'number_of_guests' => 'required|integer|min:1',
            'special_requests' => 'nullable|string',
        ]);

        $room = HotelRoom::findOrFail($validated['room_id']);

        // Check if room capacity is sufficient (using beds_count as capacity)
        if ($validated['number_of_guests'] > $room->beds_count) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'عدد الضيوف يتجاوز سعة الغرفة'
                ], 422);
            }
            return back()->withErrors(['number_of_guests' => 'عدد الضيوف يتجاوز سعة الغرفة']);
        }

        // Calculate total price
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nights = $checkIn->diffInDays($checkOut);
        $totalPrice = $room->price_per_night * $nights;

        DB::beginTransaction();
        try {
            // Create booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'hotel_id' => $validated['hotel_id'],
                'room_id' => $validated['room_id'],
                'check_in_date' => $validated['check_in_date'],
                'check_out_date' => $validated['check_out_date'],
                'guests_count' => $validated['number_of_guests'],
                'rooms_count' => 1,
                'total_price' => $totalPrice,
                'price_per_night' => $room->price_per_night,
                'status' => 'pending',
                'notes' => $validated['special_requests'] ?? null,
                'nights_count' => $nights,
            ]);

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
            return back()->withErrors(['error' => 'حدث خطأ أثناء إنشاء الحجز'])->withInput();
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
}
