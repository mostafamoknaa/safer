<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    /**
     * Display a listing of payments.
     */
    public function index(Request $request): View
    {
        $query = Payment::with(['booking.user', 'booking.hotel'])
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
            $query->where('booking_id', $request->booking_id);
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

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create(Request $request): View
    {
        $bookingId = $request->get('booking_id');
        
        if (!$bookingId) {
            abort(404, 'Booking ID is required');
        }

        $booking = Booking::with(['user', 'hotel'])->findOrFail($bookingId);

        return view('admin.payments.create', compact('booking'));
    }

    /**
     * Store a newly created payment.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'booking_id' => 'required|exists:bookings,id',
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
                ->with('error', __('admin.payments.messages.amount_exceeds_remaining', [
                    'remaining' => number_format($remainingAmount, 2)
                ]));
        }

        // Set paid_at if status is completed
        if ($validated['status'] === 'completed' && !$request->filled('paid_at')) {
            $validated['paid_at'] = now();
        }

        $payment = Payment::create($validated);

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', __('admin.payments.messages.created'));
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment): View
    {
        $payment->load(['booking.user', 'booking.hotel', 'booking.room', 'booking.payments']);

        return view('admin.payments.show', compact('payment'));
    }

    /**
     * Update the specified payment.
     */
    public function update(Request $request, Payment $payment): RedirectResponse
    {
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
            ->route('admin.payments.show', $payment)
            ->with('success', __('admin.payments.messages.updated'));
    }

    /**
     * Remove the specified payment.
     */
    public function destroy(Payment $payment): RedirectResponse
    {
        $booking = $payment->booking;
        
        $payment->delete();

        return redirect()
            ->route('admin.bookings.show', $booking)
            ->with('success', __('admin.payments.messages.deleted'));
    }
}
