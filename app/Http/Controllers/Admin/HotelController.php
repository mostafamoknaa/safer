<?php

namespace App\Http\Controllers\Admin;

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
    public function index(): View
    {
        $hotels = Hotel::with('province')->orderByDesc('created_at')->paginate(12);

        return view('admin.hotels.index', compact('hotels'));
    }

    public function create(): View
    {
        $provinces = Province::where('is_active', true)->orderBy('name_ar')->get();

        return view('admin.hotels.create', compact('provinces'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validatedData($request);

        $hotel = Hotel::create($data);

        $this->handleMedia($request, $hotel);

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', trans('admin.hotels.messages.created'));
    }

    public function edit(Hotel $hotel): View
    {
        $provinces = Province::where('is_active', true)->orderBy('name_ar')->get();
        $hotel->load('media');

        return view('admin.hotels.edit', compact('hotel', 'provinces'));
    }

    public function update(Request $request, Hotel $hotel): RedirectResponse
    {
        $data = $this->validatedData($request);

        $hotel->update($data);

        $this->handleMedia($request, $hotel);

        if ($request->has('delete_media')) {
            $this->deleteMedia($request->input('delete_media'));
        }

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', trans('admin.hotels.messages.updated'));
    }

    public function destroy(Hotel $hotel): RedirectResponse
    {
        // Delete all media files
        foreach ($hotel->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $hotel->delete();

        return redirect()
            ->route('admin.hotels.index')
            ->with('success', trans('admin.hotels.messages.deleted'));
    }

    protected function validatedData(Request $request): array
    {
        return $request->validate([
            'name_ar' => ['required', 'string', 'max:200'],
            'name_en' => ['required', 'string', 'max:200'],
            'address_ar' => ['required', 'string'],
            'address_en' => ['required', 'string'],
            'province_id' => ['required', 'exists:provinces,id'],
            'type' => ['required', 'in:hotel,hostel,spa,hotel_apartment'],
            'website_url' => ['nullable', 'url', 'max:255'],
            'about_info_ar' => ['nullable', 'string'],
            'about_info_en' => ['nullable', 'string'],
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
        $orderColumn = 0;

        // Handle images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('hotels/' . $hotel->id . '/images', 'public');
                HotelMedia::create([
                    'hotel_id' => $hotel->id,
                    'type' => 'image',
                    'file_path' => $path,
                    'order_column' => $orderColumn++,
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
