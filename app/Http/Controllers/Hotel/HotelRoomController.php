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
        $managedHotelIds = $user->managedHotels()->pluck('hotels.id');

        $query = HotelRoom::with('hotel')
            ->whereIn('hotel_id', $managedHotelIds);

        if ($request->has('hotel_id') && $request->hotel_id) {
            // التحقق من أن الفندق من الفنادق المسئول عنها
            if ($user->managesHotel($request->hotel_id)) {
                $query->where('hotel_id', $request->hotel_id);
            }
        }

        $rooms = $query->orderByDesc('created_at')->paginate(12);
        $hotels = $user->managedHotels()->where('is_active', true)->orderBy('name_ar')->get();

        return view('hotel.hotel-rooms.index', compact('rooms', 'hotels'));
    }

    /**
     * Show the form for creating a new room.
     */
    public function create(Request $request): View
    {
        $user = auth()->user();
        $hotels = $user->managedHotels()->where('is_active', true)->orderBy('name_ar')->get();
        $selectedHotelId = $request->get('hotel_id');

        // التحقق من أن الفندق المحدد من الفنادق المسئول عنها
        if ($selectedHotelId && !$user->managesHotel($selectedHotelId)) {
            $selectedHotelId = null;
        }

        return view('hotel.hotel-rooms.create', compact('hotels', 'selectedHotelId'));
    }

    /**
     * Store a newly created room.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $this->validatedData($request);

        // التحقق من أن الفندق من الفنادق المسئول عنها
        if (!$user->managesHotel($data['hotel_id'])) {
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
        // التحقق من أن المستخدم مسئول عن فندق هذه الغرفة
        if (!auth()->user()->managesHotel($hotelRoom->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذه الغرفة.');
        }

        $hotelRoom->load('media');

        return view('hotel.hotel-rooms.edit', compact('hotelRoom'));
    }

    /**
     * Update the specified room.
     */
    public function update(Request $request, HotelRoom $hotelRoom): RedirectResponse
    {
        // التحقق من أن المستخدم مسئول عن فندق هذه الغرفة
        if (!auth()->user()->managesHotel($hotelRoom->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذه الغرفة.');
        }

        $data = $this->validatedData($request, $hotelRoom);

        // منع تغيير الفندق عند التعديل
        $data['hotel_id'] = $hotelRoom->hotel_id;

        $hotelRoom->update($data);

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
        // التحقق من أن المستخدم مسئول عن فندق هذه الغرفة
        if (!auth()->user()->managesHotel($hotelRoom->hotel_id)) {
            abort(403, 'ليس لديك صلاحية لحذف هذه الغرفة.');
        }

        // Delete all media files
        foreach ($hotelRoom->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $hotelId = $hotelRoom->hotel_id;
        $hotelRoom->delete();

        return redirect()
            ->route('hotel.hotel-rooms.index', ['hotel_id' => $hotelId])
            ->with('success', trans('hotel.hotel_rooms.messages.deleted'));
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
