<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request)
    {
        $query = Event::where('is_active', true);

        // Filter by upcoming or past
        if ($request->filled('filter')) {
            if ($request->filter === 'upcoming') {
                $query->where('event_date', '>=', now());
            } elseif ($request->filter === 'past') {
                $query->where('event_date', '<', now());
            }
        } else {
            // Default: show upcoming events
            $query->where('event_date', '>=', now());
        }

        // Search by name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%");
            });
        }

        $events = $query->orderBy('event_date', 'asc')->paginate(12);

        return view('web.events.index', compact('events'));
    }

    /**
     * Display the specified event.
     */
    public function show(Event $event)
    {
        return view('web.events.show', compact('event'));
    }

    /**
     * Purchase tickets for an event.
     */
    public function purchaseTickets(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'number_of_tickets' => 'required|integer|min:1',
        ]);

        $event = Event::findOrFail($validated['event_id']);

        // Check if event is active
        if (!$event->is_active) {
            return back()->withErrors(['error' => 'هذه الفعالية غير متاحة حالياً']);
        }

        // Check if event date has passed
        if ($event->event_date < now()) {
            return back()->withErrors(['error' => 'لا يمكن شراء تذاكر لفعالية منتهية']);
        }

        // Check available tickets
        if ($event->available_tickets < $validated['number_of_tickets']) {
            return back()->withErrors(['error' => 'عدد التذاكر المتاحة غير كافٍ']);
        }

        // Calculate total price
        $totalPrice = $event->ticket_price * $validated['number_of_tickets'];

        DB::beginTransaction();
        try {
            // Create tickets
            for ($i = 0; $i < $validated['number_of_tickets']; $i++) {
                EventTicket::create([
                    'event_id' => $event->id,
                    'user_id' => auth()->id(),
                    'ticket_number' => 'TKT-' . strtoupper(uniqid()),
                    'price' => $event->ticket_price,
                    'status' => 'active',
                ]);
            }

            // Update available tickets
            $event->decrement('available_tickets', $validated['number_of_tickets']);

            DB::commit();

            return redirect()->route('web.events.my-tickets')
                ->with('success', 'تم شراء التذاكر بنجاح');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'حدث خطأ أثناء شراء التذاكر']);
        }
    }

    /**
     * Display user's tickets.
     */
    public function myTickets()
    {
        $tickets = auth()->user()->eventTickets()
            ->with('event')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('web.events.my-tickets', compact('tickets'));
    }
}
