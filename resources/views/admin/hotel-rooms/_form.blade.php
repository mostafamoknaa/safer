@php
    $editing = isset($hotelRoom);
@endphp

<div class="grid gap-6">
    @if($editing)
        {{-- في حالة التعديل: عرض الفندق فقط (read-only) --}}
        <div class="grid gap-2">
            <label class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.hotel') }}
            </label>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <div class="flex items-center gap-2">
                    <i class="fas fa-hotel text-slate-400"></i>
                    <span>{{ app()->getLocale() === 'ar' ? $hotelRoom->hotel->name_ar : $hotelRoom->hotel->name_en }}</span>
                </div>
            </div>
            <input type="hidden" name="hotel_id" value="{{ $hotelRoom->hotel_id }}">
        </div>
    @else
        {{-- في حالة الإنشاء: dropdown للاختيار --}}
        <div class="grid gap-2">
            <label for="hotel_id" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.hotel') }}
            </label>
            <select id="hotel_id" name="hotel_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('admin.hotel_rooms.form.hotel') }}</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}" @selected(old('hotel_id', $selectedHotelId ?? '') == $hotel->id)>
                        {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                    </option>
                @endforeach
            </select>
            @error('hotel_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    @endif

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="price_per_night" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.price_per_night') }}
            </label>
            <input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0"
                   value="{{ old('price_per_night', $hotelRoom->price_per_night ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('price_per_night')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="beds_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.beds_count') }}
            </label>
            <input id="beds_count" name="beds_count" type="number" min="1"
                   value="{{ old('beds_count', $hotelRoom->beds_count ?? '1') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('beds_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="bathrooms_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.bathrooms_count') }}
            </label>
            <input id="bathrooms_count" name="bathrooms_count" type="number" min="1"
                   value="{{ old('bathrooms_count', $hotelRoom->bathrooms_count ?? '1') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('bathrooms_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="rooms_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotel_rooms.form.rooms_count') }}
            </label>
            <input id="rooms_count" name="rooms_count" type="number" min="1"
                   value="{{ old('rooms_count', $hotelRoom->rooms_count ?? '1') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('rooms_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="images" class="text-sm font-medium text-slate-600">
            {{ __('admin.hotel_rooms.form.images') }}
        </label>
        <input id="images" name="images[]" type="file" multiple accept="image/*"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        <p class="text-xs text-slate-400">{{ __('admin.hotel_rooms.form.images_hint') }}</p>
        @error('images.*')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @if($editing && $hotelRoom->media->count() > 0)
        <div class="grid gap-4">
            <label class="text-sm font-medium text-slate-600">الصور الحالية</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($hotelRoom->media as $media)
                    <div class="relative group">
                        <img src="{{ Storage::url($media->file_path) }}" alt="Room Image" class="w-full h-32 object-cover rounded-lg">
                        <label class="absolute top-2 right-2 flex items-center gap-1">
                            <input type="checkbox" name="delete_media[]" value="{{ $media->id }}" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                            <span class="text-xs text-white bg-rose-500 px-2 py-1 rounded">حذف</span>
                        </label>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="flex items-center gap-3">
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $hotelRoom->is_active ?? true))
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            {{ __('admin.hotel_rooms.form.is_active') }}
        </label>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.hotel-rooms.index', request()->has('hotel_id') ? ['hotel_id' => request()->hotel_id] : []) }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('admin.hotel_rooms.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('admin.hotel_rooms.actions.update') : __('admin.hotel_rooms.actions.store') }}
        </button>
    </div>
</div>

