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
        <!-- Current Images ... -->
    @endif

    <!-- Room Services -->
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
                    $roomServices = old('services', $hotelRoom->services ?? []);
                @endphp
                @foreach($services as $service)
                    <label class="service-item flex items-center gap-3 p-3 bg-white rounded-lg border border-slate-200 hover:border-indigo-300 hover:bg-indigo-50 cursor-pointer transition-all duration-200 group" data-service="{{ __('hotel.hotels.services.' . $service) }}">
                        <input type="checkbox" name="services[]" value="{{ $service }}"
                               class="w-4 h-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 focus:ring-2"
                               @checked(in_array($service, $roomServices))>
                        <span class="text-sm text-slate-700 group-hover:text-indigo-700 font-medium">{{ __('hotel.hotels.services.' . $service) }}</span>
                    </label>
                @endforeach
            </div>
        </div>
        @error('services')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <!-- Room Availability & Time Slots -->
    <div class="grid gap-4">
        <label class="text-sm font-medium text-slate-600">
            {{ __('hotel.hotel_rooms.form.availability') ?? 'توفر الغرفة والفترات الزمنية' }}
        </label>
        <div class="bg-slate-50 rounded-xl p-6 border border-slate-200">
            <!-- Default Operating Hours -->
            <div class="mb-6">
                <h4 class="text-sm font-medium text-slate-700 mb-3">{{ __('hotel.hotel_rooms.form.default_hours') ?? 'ساعات العمل الافتراضية' }}</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-xs text-slate-600">{{ __('hotel.hotel_rooms.form.checkin_time') ?? 'وقت تسجيل الدخول' }}</label>
                        <input type="time" name="checkin_time" value="{{ old('checkin_time', isset($hotelRoom) && $hotelRoom->checkin_time ? $hotelRoom->checkin_time : '14:00') }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                    <div>
                        <label class="text-xs text-slate-600">{{ __('hotel.hotel_rooms.form.checkout_time') ?? 'وقت تسجيل الخروج' }}</label>
                        <input type="time" name="checkout_time" value="{{ old('checkout_time', isset($hotelRoom) && $hotelRoom->checkout_time ? $hotelRoom->checkout_time : '12:00') }}"
                               class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    </div>
                </div>
            </div>

            <!-- Blocked Dates/Times -->
            <div class="mb-4">
                <h4 class="text-sm font-medium text-slate-700 mb-3">{{ __('hotel.hotel_rooms.form.blocked_slots') ?? 'حجب تواريخ/أوقات محددة' }}</h4>
                <div id="blockedSlots" class="space-y-3">
                    @php
                        $blockedSlots = old('blocked_slots', isset($hotelRoom) && $hotelRoom->blocked_slots ? $hotelRoom->blocked_slots : []);
                    @endphp
                    @if(empty($blockedSlots))
                        <div class="blocked-slot grid grid-cols-12 gap-3 items-end">
                            <div class="col-span-3">
                                <label class="text-xs text-slate-600">من تاريخ</label>
                                <input type="date" name="blocked_slots[0][from_date]" 
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-slate-600">من وقت</label>
                                <input type="time" name="blocked_slots[0][from_time]" 
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>
                            <div class="col-span-3">
                                <label class="text-xs text-slate-600">إلى تاريخ</label>
                                <input type="date" name="blocked_slots[0][to_date]" 
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>
                            <div class="col-span-2">
                                <label class="text-xs text-slate-600">إلى وقت</label>
                                <input type="time" name="blocked_slots[0][to_time]" 
                                       class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            </div>
                            <div class="col-span-2">
                                <button type="button" onclick="removeBlockedSlot(this)" 
                                        class="w-full px-3 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 text-sm">
                                    حذف
                                </button>
                            </div>
                        </div>
                    @else
                        @foreach($blockedSlots as $index => $slot)
                            <div class="blocked-slot grid grid-cols-12 gap-3 items-end">
                                <div class="col-span-3">
                                    <label class="text-xs text-slate-600">من تاريخ</label>
                                    <input type="date" name="blocked_slots[{{ $index }}][from_date]" value="{{ $slot['from_date'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs text-slate-600">من وقت</label>
                                    <input type="time" name="blocked_slots[{{ $index }}][from_time]" value="{{ $slot['from_time'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <div class="col-span-3">
                                    <label class="text-xs text-slate-600">إلى تاريخ</label>
                                    <input type="date" name="blocked_slots[{{ $index }}][to_date]" value="{{ $slot['to_date'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <div class="col-span-2">
                                    <label class="text-xs text-slate-600">إلى وقت</label>
                                    <input type="time" name="blocked_slots[{{ $index }}][to_time]" value="{{ $slot['to_time'] ?? '' }}"
                                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                                </div>
                                <div class="col-span-2">
                                    <button type="button" onclick="removeBlockedSlot(this)" 
                                            class="w-full px-3 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 text-sm">
                                        حذف
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <button type="button" onclick="addBlockedSlot()" 
                        class="mt-3 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    إضافة فترة محجوبة
                </button>
            </div>
        </div>
        @error('blocked_slots')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    @push('scripts')
    <script>
    let slotIndex = {{ count(old('blocked_slots', isset($hotelRoom) && $hotelRoom->blocked_slots ? $hotelRoom->blocked_slots : [])) }};
    
    function addBlockedSlot() {
        const container = document.getElementById('blockedSlots');
        const slotHtml = `
            <div class="blocked-slot grid grid-cols-12 gap-3 items-end">
                <div class="col-span-3">
                    <label class="text-xs text-slate-600">من تاريخ</label>
                    <input type="date" name="blocked_slots[${slotIndex}][from_date]" 
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div class="col-span-2">
                    <label class="text-xs text-slate-600">من وقت</label>
                    <input type="time" name="blocked_slots[${slotIndex}][from_time]" 
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div class="col-span-3">
                    <label class="text-xs text-slate-600">إلى تاريخ</label>
                    <input type="date" name="blocked_slots[${slotIndex}][to_date]" 
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div class="col-span-2">
                    <label class="text-xs text-slate-600">إلى وقت</label>
                    <input type="time" name="blocked_slots[${slotIndex}][to_time]" 
                           class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>
                <div class="col-span-2">
                    <button type="button" onclick="removeBlockedSlot(this)" 
                            class="w-full px-3 py-2 bg-rose-500 text-white rounded-lg hover:bg-rose-600 text-sm">
                        حذف
                    </button>
                </div>
            </div>
        `;
        container.insertAdjacentHTML('beforeend', slotHtml);
        slotIndex++;
    }
    
    function removeBlockedSlot(button) {
        button.closest('.blocked-slot').remove();
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('serviceSearch');
        const newServiceInput = document.getElementById('newService');
        const addServiceBtn = document.getElementById('addService');
        const servicesContainer = document.getElementById('servicesContainer');
        
        if (searchInput) {
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
        }
        
        if (addServiceBtn) {
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
        }
        
        if (newServiceInput) {
            newServiceInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    addServiceBtn.click();
                }
            });
        }
    });
    </script>
    @endpush

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

