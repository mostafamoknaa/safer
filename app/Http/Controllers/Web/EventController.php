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
        $tripsQuery = \App\Models\Trip::where('is_active', true);
        $eventsQuery = \App\Models\Event::where('is_active', true);

        // Search by location for trips
        if ($request->filled('search')) {
            $search = $request->search;
            $tripsQuery->where(function ($q) use ($search) {
                $q->where('departure_location_ar', 'like', "%{$search}%")
                    ->orWhere('departure_location_en', 'like', "%{$search}%")
                    ->orWhere('arrival_location_ar', 'like', "%{$search}%")
                    ->orWhere('arrival_location_en', 'like', "%{$search}%");
            });
            $eventsQuery->where(function ($q) use ($search) {
                $q->where('name_ar', 'like', "%{$search}%")
                    ->orWhere('name_en', 'like', "%{$search}%")
                    ->orWhere('location_ar', 'like', "%{$search}%")
                    ->orWhere('location_en', 'like', "%{$search}%");
            });
        }

        $trips = $tripsQuery->orderBy('trip_date', 'asc')->get();
        $discoveryEvents = $eventsQuery->orderBy('event_date', 'asc')->get();

        return view('web.events.index', compact('trips', 'discoveryEvents'));
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
        $totalPrice = $event->price_per_person * $validated['number_of_tickets'];

        DB::beginTransaction();
        try {
            // Create ticket record
            EventTicket::create([
                'event_id' => $event->id,
                'user_id' => auth()->id(),
                'tickets_count' => $validated['number_of_tickets'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $request->input('notes'),
            ]);

            // Update available tickets
            $event->decrement('available_tickets', $validated['number_of_tickets']);

            DB::commit();

            return redirect()->route('web.bookings.index', ['tab' => 'hotels'])
                ->with('success', 'تم طلب التذاكر بنجاح، بانتظار التأكيد');
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
