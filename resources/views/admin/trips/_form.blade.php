@php
    $editing = isset($trip);
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="departure_location_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.departure_location_ar') }}
            </label>
            <input id="departure_location_ar" name="departure_location_ar" type="text"
                   value="{{ old('departure_location_ar', $trip->departure_location_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('departure_location_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="departure_location_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.departure_location_en') }}
            </label>
            <input id="departure_location_en" name="departure_location_en" type="text"
                   value="{{ old('departure_location_en', $trip->departure_location_en ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr"
                   required>
            @error('departure_location_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="arrival_location_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.arrival_location_ar') }}
            </label>
            <input id="arrival_location_ar" name="arrival_location_ar" type="text"
                   value="{{ old('arrival_location_ar', $trip->arrival_location_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('arrival_location_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="arrival_location_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.arrival_location_en') }}
            </label>
            <input id="arrival_location_en" name="arrival_location_en" type="text"
                   value="{{ old('arrival_location_en', $trip->arrival_location_en ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr"
                   required>
            @error('arrival_location_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="bus_id" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.bus') }}
            </label>
            <select id="bus_id" name="bus_id"
                    class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                    required>
                <option value="">{{ __('admin.trips.form.bus') }}</option>
                @foreach($buses as $bus)
                    <option value="{{ $bus->id }}" @selected(old('bus_id', $trip->bus_id ?? '') == $bus->id)>
                        {{ app()->getLocale() === 'ar' ? $bus->name_ar : $bus->name_en }}
                    </option>
                @endforeach
            </select>
            @error('bus_id')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="price" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.price') }}
            </label>
            <input id="price" name="price" type="number" step="0.01" min="0"
                   value="{{ old('price', $trip->price ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('price')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="grid gap-2">
            <label for="trip_date" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.trip_date') }}
            </label>
            <input id="trip_date" name="trip_date" type="date"
                   value="{{ old('trip_date', isset($trip) && $trip->trip_date ? $trip->trip_date->format('Y-m-d') : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('trip_date')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="trip_time" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.trip_time') }}
            </label>
            <input id="trip_time" name="trip_time" type="time"
                   value="{{ old('trip_time', $trip->trip_time ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('trip_time')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="duration_minutes" class="text-sm font-medium text-slate-600">
                {{ __('admin.trips.form.duration_minutes') }}
            </label>
            <input id="duration_minutes" name="duration_minutes" type="number" min="1"
                   value="{{ old('duration_minutes', $trip->duration_minutes ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('duration_minutes')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $trip->is_active ?? true))
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm font-medium text-slate-600">{{ __('admin.trips.form.is_active') }}</span>
        </label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('admin.trips.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            <i class="fas fa-times"></i>
            {{ __('admin.trips.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-save"></i>
            {{ $editing ? __('admin.trips.actions.update') : __('admin.trips.actions.store') }}
        </button>
    </div>
</div>

