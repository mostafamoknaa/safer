<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments for managed hotels.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $managedHotelIds = $user->managedHotels()->pluck('hotels.id');

        $query = Payment::with(['booking.user', 'booking.hotel'])
            ->whereHas('booking', function ($q) use ($managedHotelIds) {
                $q->whereIn('hotel_id', $managedHotelIds);
            })
            ->orderByDesc('created_at');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Filter by booking
        if ($request->filled('booking_id')) {
            $booking = Booking::find($request->booking_id);
            if ($booking && $user->managesHotel($booking->hotel_id)) {
                $query->where('booking_id', $request->booking_id);
            }
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('booking', function ($q) use ($search) {
                        $q->where('booking_reference', 'like', "%{$search}%")
                            ->orWhereHas('user', function ($q) use ($search) {
                                $q->where('name', 'like', "%{$search}%")
                                    ->orWhere('email', 'like', "%{$search}%");
                            });
                    });
            });
        }

        $payments = $query->paginate(15)->withQueryString();

        return view('hotel.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        $bookingId = $request->get('booking_id');
        
        if (!$bookingId) {
            abort(404, 'Booking ID is required');
        }

        $booking = Booking::with(['user', 'hotel'])->findOrFail($bookingId);
        
        // Verify that the booking belongs to a managed hotel
        if (!$user->managesHotel($booking->hotel_id)) {
            abort(403, 'You do not have access to this booking.');
        }

        return view('hotel.payments.create', compact('booking'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $managedHotelIds = $user->managedHotels()->pluck('hotels.id')->toArray();

        $validated = $request->validate([
            'booking_id' => ['required', 'exists:bookings,id', function ($attribute, $value, $fail) use ($managedHotelIds) {
                $booking = Booking::find($value);
                if (!$booking || !in_array($booking->hotel_id, $managedHotelIds)) {
                    $fail('You can only create payments for bookings in your managed hotels.');
                }
            }],
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online,other',
            'status' => 'required|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'paid_at' => 'nullable|date',
        ]);

        // Check if payment amount exceeds remaining amount
        $booking = Booking::findOrFail($validated['booking_id']);
        $remainingAmount = $booking->remaining_amount;

        if ($validated['amount'] > $remainingAmount) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', __('hotel.payments.messages.amount_exceeds_remaining', [
                    'remaining' => number_format($remainingAmount, 2)
                ]));
        }

        // Set paid_at if status is completed
        if ($validated['status'] === 'completed' && !$request->filled('paid_at')) {
            $validated['paid_at'] = now();
        }

        $payment = Payment::create($validated);

        return redirect()
            ->route('hotel.bookings.show', $booking)
            ->with('success', __('hotel.payments.messages.created'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $user = auth()->user();
        
        // Verify that the payment belongs to a booking in a managed hotel
        if (!$user->managesHotel($payment->booking->hotel_id)) {
            abort(403, 'You do not have access to this payment.');
        }

        $payment->load(['booking.user', 'booking.hotel', 'booking.room', 'booking.payments']);

        return view('hotel.payments.show', compact('payment'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
        $user = auth()->user();
        
        // Verify that the payment belongs to a booking in a managed hotel
        if (!$user->managesHotel($payment->booking->hotel_id)) {
            abort(403, 'You do not have access to this payment.');
        }

        $validated = $request->validate([
            'amount' => 'required|numeric|min:0.01',
            'payment_method' => 'required|in:cash,card,bank_transfer,online,other',
            'status' => 'required|in:pending,completed,failed,refunded',
            'transaction_id' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:1000',
            'paid_at' => 'nullable|date',
        ]);

        // Set paid_at if status is completed
        if ($validated['status'] === 'completed' && !$payment->paid_at) {
            $validated['paid_at'] = $request->filled('paid_at') ? $request->paid_at : now();
        }

        $payment->update($validated);

        return redirect()
            ->route('hotel.payments.show', $payment)
            ->with('success', __('hotel.payments.messages.updated'));
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $user = auth()->user();
        
        // Verify that the payment belongs to a booking in a managed hotel
        if (!$user->managesHotel($payment->booking->hotel_id)) {
            abort(403, 'You do not have access to this payment.');
        }

        $booking = $payment->booking;
        
        $payment->delete();

        return redirect()
            ->route('hotel.bookings.show', $booking)
            ->with('success', __('hotel.payments.messages.deleted'));
    }
}
