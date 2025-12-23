<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventTicket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class EventController extends Controller
{
    /**
     * Get available events.
     */
    public function getEvents(Request $request): JsonResponse
    {
        $query = Event::where('is_active', true)
            ->whereDate('event_date', '>=', now());

        if ($request->filled('date')) {
            $query->whereDate('event_date', $request->date);
        }

        $events = $query->orderBy('event_date')
            ->get()
            ->map(function ($event) {
                return [
                    'id' => $event->id,
                    'name' => app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en,
                    'location' => app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en,
                    'location_url' => $event->location_url,
                    'event_date' => $event->event_date->format('Y-m-d H:i:s'),
                    'description' => app()->getLocale() === 'ar' ? $event->description_ar : $event->description_en,
                    'price' => (float) $event->price,
                    'available_tickets' => $event->available_tickets,
                    'remaining_tickets' => $event->remaining_tickets,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $events,
        ]);
    }

    /**
     * Get event details.
     */
    public function getEventDetails(Event $event): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => [
                'id' => $event->id,
                'name' => app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en,
                'location' => app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en,
                'location_url' => $event->location_url,
                'event_date' => $event->event_date->format('Y-m-d H:i:s'),
                'description' => app()->getLocale() === 'ar' ? $event->description_ar : $event->description_en,
                'price' => (float) $event->price,
                'available_tickets' => $event->available_tickets,
                'remaining_tickets' => $event->remaining_tickets,
                'sold_tickets' => $event->sold_tickets_count,
            ],
        ]);
    }

    /**
     * Purchase event tickets.
     */
    public function purchaseTickets(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'event_id' => 'required|exists:events,id',
                'tickets_count' => 'required|integer|min:1|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $event = Event::findOrFail($validated['event_id']);

            // Check if event is active
            if (!$event->is_active) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.events.event_not_available'),
                ], 400);
            }

            // Check available tickets
            if ($validated['tickets_count'] > $event->remaining_tickets) {
                return response()->json([
                    'success' => false,
                    'message' => __('api.events.not_enough_tickets', [
                        'available' => $event->remaining_tickets
                    ]),
                ], 400);
            }

            // Calculate total price
            $totalPrice = $event->price * $validated['tickets_count'];

            // Create ticket
            $ticket = EventTicket::create([
                'user_id' => Auth::id(),
                'event_id' => $event->id,
                'tickets_count' => $validated['tickets_count'],
                'total_price' => $totalPrice,
                'status' => 'pending',
                'notes' => $validated['notes'] ?? null,
            ]);

            return response()->json([
                'success' => true,
                'message' => __('api.events.tickets_purchased'),
                'data' => [
                    'ticket_id' => $ticket->id,
                    'ticket_reference' => $ticket->ticket_reference,
                    'tickets_count' => $ticket->tickets_count,
                    'total_price' => $totalPrice,
                ],
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => __('api.validation_error'),
                'errors' => $e->errors(),
            ], 422);
        }
    }

    /**
     * Get user event tickets.
     */
    public function getUserTickets(): JsonResponse
    {
        $tickets = EventTicket::where('user_id', Auth::id())
            ->with('event')
            ->orderByDesc('created_at')
            ->get()
            ->map(function ($ticket) {
                return [
                    'id' => $ticket->id,
                    'ticket_reference' => $ticket->ticket_reference,
                    'event' => [
                        'id' => $ticket->event->id,
                        'name' => app()->getLocale() === 'ar' ? $ticket->event->name_ar : $ticket->event->name_en,
                        'location' => app()->getLocale() === 'ar' ? $ticket->event->location_ar : $ticket->event->location_en,
                        'event_date' => $ticket->event->event_date->format('Y-m-d H:i:s'),
                    ],
                    'tickets_count' => $ticket->tickets_count,
                    'total_price' => (float) $ticket->total_price,
                    'status' => $ticket->status,
                    'created_at' => $ticket->created_at->format('Y-m-d H:i:s'),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $tickets,
        ]);
    }
}
