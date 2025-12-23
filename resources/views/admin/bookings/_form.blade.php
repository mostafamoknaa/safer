@php
    $editing = isset($booking);
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="user_id" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.user') }}
            </label>
            <select id="user_id" name="user_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('admin.bookings.form.user') }}</option>
                @foreach($users as $user)
                    <option value="{{ $user->id }}" @selected(old('user_id', $booking->user_id ?? '') == $user->id)>
                        {{ $user->name }} ({{ $user->email }})
                    </option>
                @endforeach
            </select>
            @error('user_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="hotel_id" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.hotel') }}
            </label>
            <select id="hotel_id" name="hotel_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('admin.bookings.form.hotel') }}</option>
                @foreach($hotels as $hotel)
                    <option value="{{ $hotel->id }}" @selected(old('hotel_id', $booking->hotel_id ?? '') == $hotel->id)>
                        {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                    </option>
                @endforeach
            </select>
            @error('hotel_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="room_id" class="text-sm font-medium text-slate-600">
            {{ __('admin.bookings.form.room') }}
        </label>
        <select id="room_id" name="room_id"
                class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <option value="">{{ __('admin.bookings.form.room') }}</option>
            @if(isset($rooms) && $rooms->count() > 0)
                @foreach($rooms as $room)
                    <option value="{{ $room->id }}" @selected(old('room_id', ($booking->room_id ?? '')) == $room->id)>
                        {{ number_format($room->price_per_night, 2) }} {{ __('admin.bookings.currency') }} - {{ __('admin.bookings.table.rooms') }}: {{ $room->rooms_count }}
                    </option>
                @endforeach
            @endif
        </select>
        @error('room_id')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
        <p class="text-xs text-slate-400">{{ __('admin.bookings.form.room_hint') }}</p>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="check_in_date" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.check_in_date') }}
            </label>
            <input id="check_in_date" name="check_in_date" type="date"
                   value="{{ old('check_in_date', isset($booking) && $booking->check_in_date ? $booking->check_in_date->format('Y-m-d') : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('check_in_date')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="check_out_date" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.check_out_date') }}
            </label>
            <input id="check_out_date" name="check_out_date" type="date"
                   value="{{ old('check_out_date', isset($booking) && $booking->check_out_date ? $booking->check_out_date->format('Y-m-d') : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('check_out_date')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="grid gap-2">
            <label for="guests_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.guests_count') }}
            </label>
            <input id="guests_count" name="guests_count" type="number" min="1"
                   value="{{ old('guests_count', $booking->guests_count ?? '1') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('guests_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="rooms_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.rooms_count') }}
            </label>
            <input id="rooms_count" name="rooms_count" type="number" min="1"
                   value="{{ old('rooms_count', $booking->rooms_count ?? '1') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('rooms_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="price_per_night" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.price_per_night') }}
            </label>
            <input id="price_per_night" name="price_per_night" type="number" step="0.01" min="0"
                   value="{{ old('price_per_night', $booking->price_per_night ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('price_per_night')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="status" class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.status') }}
            </label>
            <select id="status" name="status"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="pending" @selected(old('status', $booking->status ?? 'pending') == 'pending')>
                    {{ __('admin.bookings.status.pending') }}
                </option>
                <option value="confirmed" @selected(old('status', $booking->status ?? '') == 'confirmed')>
                    {{ __('admin.bookings.status.confirmed') }}
                </option>
                <option value="checked_in" @selected(old('status', $booking->status ?? '') == 'checked_in')>
                    {{ __('admin.bookings.status.checked_in') }}
                </option>
                <option value="checked_out" @selected(old('status', $booking->status ?? '') == 'checked_out')>
                    {{ __('admin.bookings.status.checked_out') }}
                </option>
                <option value="cancelled" @selected(old('status', $booking->status ?? '') == 'cancelled')>
                    {{ __('admin.bookings.status.cancelled') }}
                </option>
            </select>
            @error('status')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label class="text-sm font-medium text-slate-600">
                {{ __('admin.bookings.form.total_price') }}
            </label>
            <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3 text-sm text-slate-600">
                <span id="total_price_display">{{ number_format(old('total_price', $booking->total_price ?? 0), 2) }}</span> {{ __('admin.bookings.currency') }}
            </div>
            <input type="hidden" id="nights_count" name="nights_count" value="{{ old('nights_count', $booking->nights_count ?? 1) }}">
        </div>
    </div>

    <div class="grid gap-2">
        <label for="notes" class="text-sm font-medium text-slate-600">
            {{ __('admin.bookings.form.notes') }}
        </label>
        <textarea id="notes" name="notes" rows="3"
                  class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('notes', $booking->notes ?? '') }}</textarea>
        @error('notes')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="admin_notes" class="text-sm font-medium text-slate-600">
            {{ __('admin.bookings.form.admin_notes') }}
        </label>
        <textarea id="admin_notes" name="admin_notes" rows="3"
                  class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('admin_notes', $booking->admin_notes ?? '') }}</textarea>
        @error('admin_notes')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('admin.bookings.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            <i class="fas fa-times"></i>
            {{ __('admin.bookings.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-save"></i>
            {{ $editing ? __('admin.bookings.actions.update') : __('admin.bookings.actions.store') }}
        </button>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkInDate = document.getElementById('check_in_date');
    const checkOutDate = document.getElementById('check_out_date');
    const pricePerNight = document.getElementById('price_per_night');
    const roomsCount = document.getElementById('rooms_count');
    const nightsCount = document.getElementById('nights_count');
    const totalPriceDisplay = document.getElementById('total_price_display');
    const hotelId = document.getElementById('hotel_id');
    const roomId = document.getElementById('room_id');

    function calculateTotal() {
        if (checkInDate.value && checkOutDate.value && pricePerNight.value && roomsCount.value) {
            const checkIn = new Date(checkInDate.value);
            const checkOut = new Date(checkOutDate.value);
            const nights = Math.max(1, Math.ceil((checkOut - checkIn) / (1000 * 60 * 60 * 24)));
            const total = parseFloat(pricePerNight.value) * nights * parseInt(roomsCount.value);
            
            if (nightsCount) nightsCount.value = nights;
            if (totalPriceDisplay) totalPriceDisplay.textContent = total.toFixed(2);
        }
    }

    if (checkInDate) checkInDate.addEventListener('change', calculateTotal);
    if (checkOutDate) checkOutDate.addEventListener('change', calculateTotal);
    if (pricePerNight) pricePerNight.addEventListener('input', calculateTotal);
    if (roomsCount) roomsCount.addEventListener('input', calculateTotal);

    // Load rooms when hotel is selected (for create)
    @if(!$editing)
    if (hotelId) {
        hotelId.addEventListener('change', function() {
            const hotelIdValue = this.value;
            if (hotelIdValue) {
                // Load rooms via AJAX or refresh page
                fetch('/admin/hotels/' + hotelIdValue + '/rooms')
                    .then(response => response.json())
                    .then(data => {
                        roomId.innerHTML = '<option value="">{{ __('admin.bookings.form.room') }}</option>';
                        data.forEach(room => {
                            const option = document.createElement('option');
                            option.value = room.id;
                            option.textContent = room.price_per_night + ' {{ __('admin.bookings.currency') }}';
                            roomId.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error loading rooms:', error));
            } else {
                roomId.innerHTML = '<option value="">{{ __('admin.bookings.form.room') }}</option>';
            }
        });
    }
    @endif
});
</script>
@endpush

