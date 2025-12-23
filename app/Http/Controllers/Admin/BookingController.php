<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Hotel;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookingController extends Controller
{
    /**
     * Display a listing of bookings.
     */
    public function index(Request $request): View
    {
        $query = Booking::with(['user', 'hotel', 'room', 'payments'])
            ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by hotel
        if ($request->filled('hotel_id')) {
            $query->where('hotel_id', $request->hotel_id);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search by booking reference
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('booking_reference', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%")
                            ->orWhere('phone', 'like', "%{$search}%");
                    });
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        $hotels = Hotel::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        $users = User::where('is_active', true)
            ->where('is_admin', false)
            ->orderBy('name')
            ->get();

        return view('admin.bookings.index', compact('bookings', 'hotels', 'users'));
    }

    /**
     * Show the form for creating a new booking.
     */
    public function create(): View
    {
        $hotels = Hotel::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        $users = User::where('is_active', true)
            ->where('is_admin', false)
            ->orderBy('name')
            ->get();

        $rooms = collect(); // Empty collection for create

        return view('admin.bookings.create', compact('hotels', 'users', 'rooms'));
    }

    /**
     * Store a newly created booking.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'nullable|exists:hotel_rooms,id',
            'check_in_date' => 'required|date|after_or_equal:today',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests_count' => 'required|integer|min:1',
            'rooms_count' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Calculate nights and total price
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nightsCount = max(1, $checkIn->diffInDays($checkOut));
        $validated['nights_count'] = $nightsCount;
        $validated['total_price'] = $validated['price_per_night'] * $nightsCount * $validated['rooms_count'];

        $booking = Booking::create($validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', __('admin.bookings.messages.created'));
    }

    /**
     * Display the specified booking.
     */
    public function show(Booking $booking): View
    {
        $booking->load(['user', 'hotel', 'room', 'payments']);

        return view('admin.bookings.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified booking.
     */
    public function edit(Booking $booking): View
    {
        $booking->load(['hotel', 'room']);

        $hotels = Hotel::where('is_active', true)
            ->orderBy('name_ar')
            ->get();

        $users = User::where('is_active', true)
            ->where('is_admin', false)
            ->orderBy('name')
            ->get();

        $rooms = $booking->hotel ? $booking->hotel->rooms()->where('is_active', true)->get() : collect();

        return view('admin.bookings.edit', compact('booking', 'hotels', 'users', 'rooms'));
    }

    /**
     * Update the specified booking.
     */
    public function update(Request $request, Booking $booking): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'hotel_id' => 'required|exists:hotels,id',
            'room_id' => 'nullable|exists:hotel_rooms,id',
            'check_in_date' => 'required|date',
            'check_out_date' => 'required|date|after:check_in_date',
            'guests_count' => 'required|integer|min:1',
            'rooms_count' => 'required|integer|min:1',
            'price_per_night' => 'required|numeric|min:0',
            'status' => 'required|in:pending,confirmed,checked_in,checked_out,cancelled',
            'notes' => 'nullable|string|max:1000',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        // Recalculate nights and total price
        $checkIn = \Carbon\Carbon::parse($validated['check_in_date']);
        $checkOut = \Carbon\Carbon::parse($validated['check_out_date']);
        $nightsCount = max(1, $checkIn->diffInDays($checkOut));
        $validated['nights_count'] = $nightsCount;
        $validated['total_price'] = $validated['price_per_night'] * $nightsCount * $validated['rooms_count'];

        $booking->update($validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', __('admin.bookings.messages.updated'));
    }

    /**
     * Remove the specified booking.
     */
    public function destroy(Booking $booking): RedirectResponse
    {
        // Only allow deletion if no payments exist or booking is cancelled
        if ($booking->payments()->count() > 0 && $booking->status !== 'cancelled') {
            return redirect()
                ->route('admin.bookings.show', $booking)
                ->with('error', __('admin.bookings.messages.cannot_delete_with_payments'));
        }

        $booking->delete();

        return redirect()
            ->route('admin.bookings.index')
            ->with('success', __('admin.bookings.messages.deleted'));
    }
}
