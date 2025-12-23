<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    /**
     * Display a listing of events.
     */
    public function index(Request $request): View
    {
        $query = Event::withCount([
            'tickets as tickets_count' => function ($query) {
                $query->where('status', 'confirmed');
            }
        ])
            ->orderByDesc('event_date');

        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true)->whereDate('event_date', '>=', now());
            } elseif ($request->status === 'past') {
                $query->whereDate('event_date', '<', now());
            }
        }

        $events = $query->paginate(15)->withQueryString();

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new event.
     */
    public function create(): View
    {
        return view('admin.events.create');
    }

    /**
     * Store a newly created event.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'location_url' => 'nullable|url|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1|max:100000',
            'is_active' => 'boolean',
        ]);

        Event::create($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', __('admin.events.messages.created'));
    }

    /**
     * Show the form for editing the specified event.
     */
    public function edit(Event $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    /**
     * Update the specified event.
     */
    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'location_url' => 'nullable|url|max:255',
            'event_date' => 'required|date|after_or_equal:today',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1|max:100000',
            'is_active' => 'boolean',
        ]);

        $event->update($validated);

        return redirect()
            ->route('admin.events.index')
            ->with('success', __('admin.events.messages.updated'));
    }

    /**
     * Remove the specified event.
     */
    public function destroy(Event $event): RedirectResponse
    {
        // Check if event has tickets
        if ($event->tickets()->count() > 0) {
            return redirect()
                ->route('admin.events.index')
                ->with('error', __('admin.events.messages.cannot_delete_with_tickets'));
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', __('admin.events.messages.deleted'));
    }

    /**
     * Show event with tickets.
     */
    public function show(Event $event): View
    {
        $event->load(['tickets.user'])->loadCount([
            'tickets as tickets_count' => function ($query) {
                $query->where('status', 'confirmed');
            }
        ]);

        return view('admin.events.show', compact('event'));
    }
}
