<?php

namespace App\Http\Controllers\Hotel;

use App\Http\Controllers\Controller;
use App\Models\PrivateCar;
use App\Models\PrivateCarMedia;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CarController extends Controller
{
    public function index(): View
    {
        $cars = PrivateCar::where('user_id', auth()->id())
            ->withCount('serviceRequests')
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('hotel.cars.index', compact('cars'));
    }

    public function create(): View
    {
        return view('hotel.cars.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'seats_count' => 'required|integer|min:1',
            'max_speed' => 'nullable|integer|min:0',
            'acceleration' => 'nullable|numeric|min:0',
            'power' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $data['user_id'] = auth()->id();
        $data['is_active'] = $request->boolean('is_active');

        $car = PrivateCar::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars/' . $car->id, 'public');
                PrivateCarMedia::create([
                    'private_car_id' => $car->id,
                    'file_path' => $path,
                    'type' => 'image',
                ]);
            }
        }

        return redirect()->route('hotel.cars.index')->with('success', 'Car created successfully');
    }

    public function edit(PrivateCar $car): View
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $car->load('media');

        return view('hotel.cars.edit', compact('car'));
    }

    public function update(Request $request, PrivateCar $car): RedirectResponse
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        $data = $request->validate([
            'name_ar' => 'required|string|max:255',
            'name_en' => 'required|string|max:255',
            'car_model' => 'required|string|max:255',
            'price_per_day' => 'required|numeric|min:0',
            'price_per_hour' => 'nullable|numeric|min:0',
            'seats_count' => 'required|integer|min:1',
            'max_speed' => 'nullable|integer|min:0',
            'acceleration' => 'nullable|numeric|min:0',
            'power' => 'nullable|integer|min:0',
            'fuel_type' => 'nullable|string|max:255',
            'transmission' => 'nullable|string|max:255',
            'notes_ar' => 'nullable|string',
            'notes_en' => 'nullable|string',
            'is_active' => 'sometimes|boolean',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:10240',
        ]);

        $data['is_active'] = $request->boolean('is_active');

        $car->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('cars/' . $car->id, 'public');
                PrivateCarMedia::create([
                    'private_car_id' => $car->id,
                    'file_path' => $path,
                    'type' => 'image',
                ]);
            }
        }

        if ($request->has('delete_media')) {
            $mediaItems = PrivateCarMedia::whereIn('id', $request->delete_media)->get();
            foreach ($mediaItems as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        return redirect()->route('hotel.cars.index')->with('success', 'Car updated successfully');
    }

    public function destroy(PrivateCar $car): RedirectResponse
    {
        if ($car->user_id !== auth()->id()) {
            abort(403);
        }

        foreach ($car->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        $car->delete();

        return redirect()->route('hotel.cars.index')->with('success', 'Car deleted successfully');
    }
}
