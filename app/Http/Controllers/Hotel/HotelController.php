<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\HotelMedia;
use App\Models\Province;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HotelController extends Controller
{
    /**
     * Display a listing of hotels managed by the current user.
     */
    public function index(): View
    {
        $user = auth()->user();
        $hotels = $user->managedHotels()
            ->with('province')
            ->withCount('rooms')
            ->orderByDesc('created_at')
            ->get();

        return view('hotel.hotels.index', compact('hotels'));
    }

    /**
     * Show the form for creating a new hotel.
     */
    public function create(): View
    {
        $provinces = Province::where('is_active', true)->orderBy('name_ar')->get();

        return view('hotel.hotels.create', compact('provinces'));
    }

    /**
     * Store a newly created hotel.
     */
    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $hotel = Hotel::create($data);

        // ربط الفندق بالمسئول الحالي
        auth()->user()->managedHotels()->attach($hotel->id);

        $this->handleMedia($request, $hotel);

        return redirect()
            ->route('hotel.hotels.index')
            ->with('success', trans('hotel.hotels.messages.created'));
    }

    /**
     * Show the form for editing the specified hotel.
     */
    public function edit(Hotel $hotel): View
    {
        // التحقق من أن المستخدم مسئول عن هذا الفندق
        if (!auth()->user()->managesHotel($hotel->id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الفندق.');
        }

        $provinces = Province::where('is_active', true)->orderBy('name_ar')->get();
        $hotel->load('media');

        return view('hotel.hotels.edit', compact('hotel', 'provinces'));
    }

    /**
     * Update the specified hotel.
     */
    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        // التحقق من أن المستخدم مسئول عن هذا الفندق
        if (!auth()->user()->managesHotel($hotel->id)) {
            abort(403, 'ليس لديك صلاحية لتعديل هذا الفندق.');
        }

        $data = $this->validatedData($request);

        $hotel->update($data);

        $this->handleMedia($request, $hotel);

        if ($request->has('delete_media')) {
            $this->deleteMedia($request->input('delete_media'));
        }

        return redirect()
            ->route('hotel.hotels.index')
            ->with('success', trans('hotel.hotels.messages.updated'));
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'name_ar' => ['required', 'string', 'max:200'],
            'name_en' => ['required', 'string', 'max:200'],
            'address_ar' => ['required', 'string'],
            'address_en' => ['required', 'string'],
            'province_id' => ['required', 'exists:provinces,id'],
            'country' => ['nullable', 'string', 'max:255'],
            'type' => ['required', 'in:hotel,hostel,spa,hotel_apartment'],
            'lat' => ['nullable', 'numeric'],
            'lang' => ['nullable', 'numeric'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'about_info_ar' => ['nullable', 'string'],
            'about_info_en' => ['nullable', 'string'],
            'services' => ['nullable', 'array'],
            'services.*' => ['string'],
            'is_active' => ['sometimes', 'boolean'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'mimes:jpeg,png,jpg,gif,webp', 'max:10240'],
            'videos' => ['nullable', 'array'],
            'videos.*' => ['file', 'mimes:mp4,avi,mov,wmv,flv', 'max:51200'],
        ]) + [
            'is_active' => $request->boolean('is_active'),
        ];
    }

    protected function handleMedia(Request $request, Hotel $hotel): void
    {
        $orderColumn = $hotel->media()->max('order_column') ?? -1;

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/' . $hotel->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => ++$orderColumn,
                ]);
            }
        }

        // Handle videos
        if ($request->hasFile('videos')) {
            foreach ($request->file('videos') as $video) {
                $path = $video->store('hotels/' . $hotel->id . '/videos', 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id,
                    'type' => 'video',
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
