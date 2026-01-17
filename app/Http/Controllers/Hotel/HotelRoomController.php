<?php

namespace App\Http\Controllers\Hotel;

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
    /**
     * Display a listing of rooms for managed hotels.
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        $managedHotelIds = Hotel::where('user_id', $user->id)->pluck('id');

        $query = HotelRoom::with('hotel')
            ->whereIn('hotel_id', $managedHotelIds);

        if ($request->has('hotel_id') && $request->hotel_id) {
            if (in_array($request->hotel_id, $managedHotelIds->toArray())) {
                $query->where('hotel_id', $request->hotel_id);
            }
        }

        $rooms = $query->orderByDesc('created_at')->paginate(12);
        $hotels = Hotel::where('user_id', $user->id)->where('is_active', true)->orderBy('name_ar')->get();

        return view('hotel.hotel-rooms.index', compact('rooms', 'hotels'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        $hotels = Hotel::where('user_id', $user->id)->where('is_active', true)->orderBy('name_ar')->get();
        $services = \App\Models\Service::where('is_active', true)->orderBy('name_ar')->get();
        $selectedHotelId = $request->get('hotel_id');

        if ($selectedHotelId) {
            $hotel = Hotel::find($selectedHotelId);
            if (!$hotel || $hotel->user_id !== $user->id) {
                $selectedHotelId = null;
            }
        }

        return view('hotel.hotel-rooms.create', compact('hotels', 'services', 'selectedHotelId'));
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $this->validatedData($request);

        $hotel = Hotel::find($data['hotel_id']);
        if (!$hotel || $hotel->user_id !== $user->id) {
            abort(403, 'ليس لديك صلاحية لإضافة غرف لهذا الفندق.');
        }

        $room = HotelRoom::create($data);

        $this->handleMedia($request, $room);

        return redirect()
            ->route('hotel.hotel-rooms.index', ['hotel_id' => $room->hotel_id])
            ->with('success', trans('hotel.hotel_rooms.messages.created'));
    }

    /**
     * Show the form for editing the specified room.
     */
    public function edit(HotelRoom $hotelRoom): View
    {
        $hotel = Hotel::find($hotelRoom->hotel_id);
        if (!$hotel || $hotel->user_id !== auth()->id()) {
            abort(403, 'ليس لديك صلاحية لتعديل هذه الغرفة.');
        }

        $services = \App\Models\Service::where('is_active', true)->orderBy('name_ar')->get();
        $hotelRoom->load('media');

        return view('hotel.hotel-rooms.edit', compact('hotelRoom', 'services'));
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        $hotel = Hotel::find($hotelRoom->hotel_id);
        if (!$hotel || $hotel->user_id !== auth()->id()) {
            abort(403, 'ليس لديك صلاحية لتعديل هذه الغرفة.');
        }

        $data = $this->validatedData($request, $hotelRoom);

        $data['hotel_id'] = $hotelRoom->hotel_id;

        \Log::info('Hotel Room Update Data:', $data);
        \Log::info('Original Room Data:', $hotelRoom->toArray());

        $hotelRoom->update($data);

        \Log::info('Room After Update:', $hotelRoom->fresh()->toArray());

        $this->handleMedia($request, $hotelRoom);

        if ($request->has('delete_media')) {
            $this->deleteMedia($request->input('delete_media'));
        }

        return redirect()
            ->route('hotel.hotel-rooms.index', ['hotel_id' => $hotelRoom->hotel_id])
            ->with('success', trans('hotel.hotel_rooms.messages.updated'));
    }

    /**
     * Remove the specified room.
     */
    public function destroy(HotelRoom $hotelRoom): RedirectResponse
    {
        $hotel = Hotel::find($hotelRoom->hotel_id);
        if (!$hotel || $hotel->user_id !== auth()->id()) {
            abort(403, 'ليس لديك صلاحية لحذف هذه الغرفة.');
        }

        foreach ($hotelRoom->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $hotelId = $hotelRoom->hotel_id;
        $hotelRoom->delete();

        return redirect()
            ->route('hotel.hotel-rooms.index', ['hotel_id' => $hotelId])
            ->with('success', trans('hotel.hotel_rooms.messages.deleted'));
    }

    /**
     * Clone the specified room.
     */
    public function clone(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        $hotel = Hotel::find($hotelRoom->hotel_id);
        if (!$hotel || $hotel->user_id !== auth()->id()) {
            abort(403, 'ليس لديك صلاحية لاستنساخ هذه الغرفة.');
        }

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
            ->route('hotel.hotel-rooms.index', ['hotel_id' => $hotelRoom->hotel_id])
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
            'services.*' => ['exists:services,id'],
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
        $orderColumn = $room->media()->max('order_column') ?? -1;

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/' . $room->hotel_id . '/rooms/' . $room->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $room->hotel_id,
                    'room_id' => $room->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => ++$orderColumn,
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
