<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'location_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'organizer' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        Event::create($request->all());

        return redirect()->route('admin.events.index')->with('success', 'تم إنشاء الفعالية بنجاح');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'location_ar' => 'required|string|max:255',
            'location_en' => 'required|string|max:255',
            'event_date' => 'required|date',
            'price' => 'required|numeric|min:0',
            'available_tickets' => 'required|integer|min:1',
            'description_ar' => 'nullable|string',
            'description_en' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            'location_url' => 'nullable|url',
            'image_url' => 'nullable|url',
            'organizer' => 'nullable|string|max:255',
            'contact_info' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        $event->update($request->all());

        return redirect()->route('admin.events.index')->with('success', 'تم تحديث الفعالية بنجاح');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        return redirect()->route('admin.events.index')->with('success', 'تم حذف الفعالية بنجاح');
    }
}