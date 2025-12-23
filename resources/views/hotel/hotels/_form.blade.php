@php
    $editing = isset($hotel);
@endphp

@if($editing)
    @method('PUT')
@endif

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="name_ar" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.name') }} - {{ __('Arabic', [], 'ar') }}
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
                {{ __('hotel.hotels.form.name') }} - {{ __('English', [], 'en') }}
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
                {{ __('hotel.hotels.form.province') }}
            </label>
            <select id="province_id" name="province_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('hotel.hotels.form.province') }}</option>
                @foreach($provinces as $province)
                    <option value="{{ $province->id }}" @selected(old('province_id', $hotel->province_id ?? '') == $province->id)>
                        {{ app()->getLocale() === 'ar' ? $province->name_ar : $province->name_en }}
                    </option>
                @endforeach
            </select>
            <p class="text-xs text-slate-400">{{ __('hotel.hotels.form.province_hint') }}</p>
            @error('province_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="country" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.country') }}
            </label>
            <input id="country" name="country" type="text"
                   value="{{ old('country', $hotel->country ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr">
            @error('country')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
         <div class="grid gap-2">
            <label for="type" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.type') }}
            </label>
            <select id="type" name="type"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="hotel" @selected(old('type', $hotel->type ?? 'hotel') == 'hotel')>
                    {{ __('hotel.hotels.types.hotel') }}
                </option>
                <option value="hostel" @selected(old('type', $hotel->type ?? '') == 'hostel')>
                    {{ __('hotel.hotels.types.hostel') }}
                </option>
                <option value="spa" @selected(old('type', $hotel->type ?? '') == 'spa')>
                    {{ __('hotel.hotels.types.spa') }}
                </option>
                <option value="hotel_apartment" @selected(old('type', $hotel->type ?? '') == 'hotel_apartment')>
                    {{ __('hotel.hotels.types.hotel_apartment') }}
                </option>
            </select>
            @error('type')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="website_url" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.website_url') }}
            </label>
            <input id="website_url" name="website_url" type="url"
                   value="{{ old('website_url', $hotel->website_url ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr">
            @error('website_url')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="lat" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.lat') }}
            </label>
            <input id="lat" name="lat" type="number" step="any"
                   value="{{ old('lat', $hotel->lat ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr">
            @error('lat')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="lang" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.lang') }}
            </label>
            <input id="lang" name="lang" type="number" step="any"
                   value="{{ old('lang', $hotel->lang ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr">
            @error('lang')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4">
        <label class="text-sm font-medium text-slate-600">
            {{ __('hotel.hotels.form.services') }}
        </label>
        <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
            <!-- Search and Add Service -->
            <div class="mb-4 flex gap-3">
                <input type="text" id="serviceSearch" placeholder="Search services..." 
                       class="flex-1 rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <div class="flex gap-2">
                    <input type="text" id="newService" placeholder="Add custom service" 
                           class="rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <button type="button" id="addService" 
                            class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        Add
                    </button>
                </div>
            </div>
            
            <div id="servicesContainer" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                @php
                    $services = ['wifi', 'parking', 'pool', 'food', 'sports_center', 'elevator', 'social_rooms', 'opening', 'kitchen', 'cooking_basics', 'dishes_silverware', 'oven', 'hot_water_kettle', 'dining_table', 'fire_extinguisher', 'first_aid_kit', 'crib', 'air_condition', 'indoor_fireplace', 'heating', 'smoke_alarm', 'washer', 'hangers', 'bed_linens', 'iron', 'clothing_storage', 'tv', 'internet_connection', 'high_chair', 'portable_fan', 'freezer', 'stove', 'microwave', 'waterfront', 'fire_pit', 'free_parking', 'beach_access', 'shampoo', 'body_soap', 'shower_gel'];
                    $hotelServices = old('services', $hotel->services ?? []);
                @endphp
                @foreach($services as $service)
                    <label class="service-item flex items-center gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition-all duration-200 group" data-service="{{ __('hotel.hotels.services.' . $service) }}">
                        <input type="checkbox" name="services[]" value="{{ $service }}"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2"
                               @checked(in_array($service, $hotelServices))>
                        <span class="text-sm text-slate-700 group-hover:text-indigo-700 font-medium">{{ __('hotel.hotels.services.' . $service) }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        @error('services')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('serviceSearch');
        const newServiceInput = document.getElementById('newService');
        const addServiceBtn = document.getElementById('addService');
        const servicesContainer = document.getElementById('servicesContainer');
        
        // Search functionality
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            const serviceItems = document.querySelectorAll('.service-item');
            
            serviceItems.forEach(item => {
                const serviceName = item.dataset.service.toLowerCase();
                if (serviceName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
        
        // Add custom service
        addServiceBtn.addEventListener('click', function() {
            const serviceName = newServiceInput.value.trim();
            if (serviceName) {
                const serviceValue = serviceName.toLowerCase().replace(/\s+/g, '_');
                
                const serviceItem = document.createElement('label');
                serviceItem.className = 'service-item flex items-center gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition-all duration-200 group';
                serviceItem.dataset.service = serviceName;
                
                serviceItem.innerHTML = `
                    <input type="checkbox" name="services[]" value="${serviceValue}"
                           class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2" checked>
                    <span class="text-sm text-slate-700 group-hover:text-indigo-700 font-medium">${serviceName}</span>
                    <button type="button" class="ml-auto text-rose-500 hover:text-rose-700" onclick="this.parentElement.remove()">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                `;
                
                servicesContainer.appendChild(serviceItem);
                newServiceInput.value = '';
            }
        });
        
        // Add service on Enter key
        newServiceInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addServiceBtn.click();
            }
        });
    });
    </script>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="address_ar" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.address') }} - {{ __('Arabic', [], 'ar') }}
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
                {{ __('hotel.hotels.form.address') }} - {{ __('English', [], 'en') }}
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
                {{ __('hotel.hotels.form.about_info') }} - {{ __('Arabic', [], 'ar') }}
            </label>
            <textarea id="about_info_ar" name="about_info_ar" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="rtl">{{ old('about_info_ar', $hotel->about_info_ar ?? '') }}</textarea>
            <p class="text-xs text-slate-400">{{ __('hotel.hotels.form.about_info_hint') }}</p>
            @error('about_info_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="about_info_en" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.about_info') }} - {{ __('English', [], 'en') }}
            </label>
            <textarea id="about_info_en" name="about_info_en" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="ltr">{{ old('about_info_en', $hotel->about_info_en ?? '') }}</textarea>
            <p class="text-xs text-slate-400">{{ __('hotel.hotels.form.about_info_hint') }}</p>
            @error('about_info_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="images" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.images') }}
            </label>
            <input id="images" name="images[]" type="file" multiple accept="image/*"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <p class="text-xs text-slate-400">{{ __('hotel.hotels.form.images_hint') }}</p>
            @error('images.*')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="videos" class="text-sm font-medium text-slate-600">
                {{ __('hotel.hotels.form.videos') }}
            </label>
            <input id="videos" name="videos[]" type="file" multiple accept="video/*"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <p class="text-xs text-slate-400">{{ __('hotel.hotels.form.videos_hint') }}</p>
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
            {{ __('hotel.hotels.form.is_active') }}
        </label>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('hotel.hotels.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('hotel.hotels.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('hotel.hotels.actions.update') : __('hotel.hotels.actions.store') }}
        </button>
    </div>
</div>
