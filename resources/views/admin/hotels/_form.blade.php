@php
    $editing = isset($hotel);
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="name_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.name') }} - {{ __('Arabic', [], 'ar') }}
            </label>
            <input id="name_ar" name="name_ar" type="text"
                   value="{{ old('name_ar', $hotel->name_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('name_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="name_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.name') }} - {{ __('English', [], 'en') }}
            </label>
            <input id="name_en" name="name_en" type="text"
                   value="{{ old('name_en', $hotel->name_en ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr"
                   required>
            @error('name_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="province_id" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.province') }}
            </label>
            <select id="province_id" name="province_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('admin.hotels.form.province') }}</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" @selected(old('province_id', $hotel->province_id ?? '') == $province->id)>
                        {{ app()->getLocale() === 'ar' ? $province->name_ar : $province->name_en }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400">{{ __('admin.hotels.form.province_hint') }}</p>
            @error('province_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="type" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.type') }}
            </label>
            <select id="type" name="type"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="hotel" @selected(old('type', $hotel->type ?? 'hotel') == 'hotel')>
                    {{ __('admin.hotels.types.hotel') }}
                </option>
                <option value="hostel" @selected(old('type', $hotel->type ?? '') == 'hostel')>
                    {{ __('admin.hotels.types.hostel') }}
                </option>
                <option value="spa" @selected(old('type', $hotel->type ?? '') == 'spa')>
                    {{ __('admin.hotels.types.spa') }}
                </option>
                <option value="hotel_apartment" @selected(old('type', $hotel->type ?? '') == 'hotel_apartment')>
                    {{ __('admin.hotels.types.hotel_apartment') }}
                </option>
            </select>
            @error('type')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="website_url" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.website_url') }}
            </label>
            <input id="website_url" name="website_url" type="url"
                   value="{{ old('website_url', $hotel->website_url ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr">
            @error('website_url')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div></div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="address_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.address') }} - {{ __('Arabic', [], 'ar') }}
            </label>
            <textarea id="address_ar" name="address_ar" rows="2"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="rtl"
                      required>{{ old('address_ar', $hotel->address_ar ?? '') }}</textarea>
            @error('address_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="address_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.address') }} - {{ __('English', [], 'en') }}
            </label>
            <textarea id="address_en" name="address_en" rows="2"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="ltr"
                      required>{{ old('address_en', $hotel->address_en ?? '') }}</textarea>
            @error('address_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="about_info_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.about_info') }} - {{ __('Arabic', [], 'ar') }}
            </label>
            <textarea id="about_info_ar" name="about_info_ar" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="rtl">{{ old('about_info_ar', $hotel->about_info_ar ?? '') }}</textarea>
            <p class="text-xs text-slate-400">{{ __('admin.hotels.form.about_info_hint') }}</p>
            @error('about_info_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="about_info_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.about_info') }} - {{ __('English', [], 'en') }}
            </label>
            <textarea id="about_info_en" name="about_info_en" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="ltr">{{ old('about_info_en', $hotel->about_info_en ?? '') }}</textarea>
            <p class="text-xs text-slate-400">{{ __('admin.hotels.form.about_info_hint') }}</p>
            @error('about_info_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="images" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.images') }}
            </label>
            <input id="images" name="images[]" type="file" multiple accept="image/*"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <p class="text-xs text-slate-400">{{ __('admin.hotels.form.images_hint') }}</p>
            @error('images.*')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="videos" class="text-sm font-medium text-slate-600">
                {{ __('admin.hotels.form.videos') }}
            </label>
            <input id="videos" name="videos[]" type="file" multiple accept="video/*"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <p class="text-xs text-slate-400">{{ __('admin.hotels.form.videos_hint') }}</p>
            @error('videos.*')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    @if($editing && $hotel->media->count() > 0)
        <div class="grid gap-4">
            <label class="text-sm font-medium text-slate-600">الوسائط الحالية</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                @foreach($hotel->media as $media)
                    <div class="relative group">
                        @if($media->type === 'image')
                            <img src="{{ Storage::url($media->file_path) }}" alt="Media" class="w-full h-32 object-cover rounded-lg">
                        @else
                            <video src="{{ Storage::url($media->file_path) }}" class="w-full h-32 object-cover rounded-lg" controls></video>
                        @endif
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
                   @checked(old('is_active', $hotel->is_active ?? true))
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            {{ __('admin.hotels.form.is_active') }}
        </label>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.hotels.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('admin.hotels.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('admin.hotels.actions.update') : __('admin.hotels.actions.store') }}
        </button>
    </div>
</div>
