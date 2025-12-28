<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelMedia;
use App\Models\HotelRoom;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HotelRoomController extends Controller
{
    public function index(Request $request): View
    {
        $query = HotelRoom::with('hotel');

        if ($request->has('hotel_id') && $request->hotel_id) {
            $query->where('hotel_id', $request->hotel_id);
        }

        $rooms = $query->orderByDesc('created_at')->paginate(12);
        $hotels = Hotel::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.hotel-rooms.index', compact('rooms', 'hotels'));
    }

    public function create(Request $request): View
    {
        $hotels = Hotel::where('is_active', true)->orderBy('name_ar')->get();
        $selectedHotelId = $request->get('hotel_id');

        return view('admin.hotel-rooms.create', compact('hotels', 'selectedHotelId'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $room = HotelRoom::create($data);

        $this->handleMedia($request, $room);

        return redirect()
            ->route('admin.hotel-rooms.index', ['hotel_id' => $room->hotel_id])
            ->with('success', trans('admin.hotel_rooms.messages.created'));
    }

    public function edit(HotelRoom $hotelRoom): View
    {
        $hotels = Hotel::where('is_active', true)->orderBy('name_ar')->get();
        $hotelRoom->load('media');

        return view('admin.hotel-rooms.edit', compact('hotelRoom', 'hotels'));
    }

    public function update(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        $data = $this->validatedData($request, $hotelRoom);

        // منع تغيير الفندق عند التعديل
        $data['hotel_id'] = $hotelRoom->hotel_id;

        // Debug: Log the data being updated
        \Log::info('Admin Hotel Room Update Data:', $data);
        \Log::info('Admin Original Room Data:', $hotelRoom->toArray());

        $hotelRoom->update($data);

        // Debug: Log after update
        \Log::info('Admin Room After Update:', $hotelRoom->fresh()->toArray());

        $this->handleMedia($request, $hotelRoom);

        if ($request->has('delete_media')) {
            $this->deleteMedia($request->input('delete_media'));
        }

        return redirect()
            ->route('admin.hotel-rooms.index', ['hotel_id' => $hotelRoom->hotel_id])
            ->with('success', trans('admin.hotel_rooms.messages.updated'));
    }

    public function destroy(HotelRoom $hotelRoom): RedirectResponse
    {
        // Delete all media files
        foreach ($hotelRoom->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $hotelRoom->delete();

        return redirect()
            ->route('admin.hotel-rooms.index', ['hotel_id' => $hotelRoom->hotel_id])
            ->with('success', trans('admin.hotel_rooms.messages.deleted'));
    }

    public function clone(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        $request->validate([
            'clone_count' => 'required|integer|min:1|max:50'
        ]);

        $cloneCount = $request->input('clone_count');
        
        for ($i = 0; $i < $cloneCount; $i++) {
            $clonedRoom = $hotelRoom->replicate();
            $clonedRoom->save();

            // Clone media files
            foreach ($hotelRoom->media as $media) {
                $originalPath = $media->file_path;
                $extension = pathinfo($originalPath, PATHINFO_EXTENSION);
                $newPath = 'hotels/' . $clonedRoom->hotel_id . '/rooms/' . $clonedRoom->id . '/images/' . uniqid() . '.' . $extension;
                
                if (Storage::disk('public')->exists($originalPath)) {
                    Storage::disk('public')->copy($originalPath, $newPath);
                    
                    HotelMedia::create([
                        'hotel_id' => $clonedRoom->hotel_id,
                        'room_id' => $clonedRoom->id,
                        'type' => $media->type,
                        'file_path' => $newPath,
                        'order_column' => $media->order_column,
                    ]);
                }
            }
        }

        return redirect()
            ->route('admin.hotel-rooms.index', ['hotel_id' => $hotelRoom->hotel_id])
            ->with('success', "تم استنساخ الغرفة {$cloneCount} مرة بنجاح");
    }

    protected function validatedData(Request $request, ?HotelRoom $hotelRoom = null): array
    {
        $rules = [
            'price_per_night' => ['required', 'numeric', 'min:0'],
            'beds_count' => ['required', 'integer', 'min:1'],
            'bathrooms_count' => ['required', 'integer', 'min:1'],
            'rooms_count' => ['required', 'integer', 'min:1'],
            'is_active' => ['sometimes', 'boolean'],
            'checkin_time' => ['nullable', 'date_format:H:i'],
            'checkout_time' => ['nullable', 'date_format:H:i'],
            'services' => ['nullable', 'array'],
            'blocked_slots' => ['nullable', 'array'],
            'blocked_slots.*.from_date' => ['nullable', 'date'],
            'blocked_slots.*.from_time' => ['nullable', 'date_format:H:i'],
            'blocked_slots.*.to_date' => ['nullable', 'date'],
            'blocked_slots.*.to_time' => ['nullable', 'date_format:H:i'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
        ];

        // hotel_id مطلوب فقط عند الإنشاء
        if (!$hotelRoom) {
            $rules['hotel_id'] = ['required', 'exists:hotels,id'];
        }

        $validated = $request->validate($rules);
        
        // Filter out empty blocked slots
        if (isset($validated['blocked_slots'])) {
            $validated['blocked_slots'] = array_filter($validated['blocked_slots'], function($slot) {
                return !empty($slot['from_date']) || !empty($slot['to_date']);
            });
        }

        return $validated + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function handleMedia(Request $request, HotelRoom $room): void
    {
        $orderColumn = 0;

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/' . $room->hotel_id . '/rooms/' . $room->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $room->hotel_id,
                    'room_id' => $room->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => $orderColumn++,
                ]);
            }
        }
    }

    protected function deleteMedia(array $mediaIds): void
    {
        $mediaItems = HotelMedia::whereIn('id', $mediaIds)->get();
        foreach ($mediaItems as $media) {
            Storage::disk('public')->delete($media->file_path);
            $media->delete();
        }
    }
}
