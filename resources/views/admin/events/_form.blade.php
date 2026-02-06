@php
    $editing = isset($event);
    $event = $event ?? null;
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="name_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.name_ar') }}
            </label>
            <input id="name_ar" name="name_ar" type="text"
                   value="{{ old('name_ar', $event->name_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('name_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="name_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.name_en') }}
            </label>
            <input id="name_en" name="name_en" type="text"
                   value="{{ old('name_en', isset($event) ? $event->name_en : '') }}"
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
            <label for="location_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.location_ar') }}
            </label>
            <input id="location_ar" name="location_ar" type="text"
                   value="{{ old('location_ar', isset($event) ? $event->location_ar : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('location_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="location_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.location_en') }}
            </label>
            <input id="location_en" name="location_en" type="text"
                   value="{{ old('location_en', isset($event) ? $event->location_en : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="ltr"
                   required>
            @error('location_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="location_url" class="text-sm font-medium text-slate-600">
            {{ __('admin.events.form.location_url') }}
        </label>
        <input id="location_url" name="location_url" type="url"
               value="{{ old('location_url', isset($event) ? $event->location_url : '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
               placeholder="https://maps.google.com/...">
        @error('location_url')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="grid gap-2">
            <label for="event_date" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.event_date') }}
            </label>
            <input id="event_date" name="event_date" type="datetime-local"
                   value="{{ old('event_date', isset($event) && $event->event_date ? $event->event_date->format('Y-m-d\TH:i') : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('event_date')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="price" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.price') }}
            </label>
            <input id="price" name="price" type="number" step="0.01" min="0"
                   value="{{ old('price', isset($event) ? $event->price : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('price')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="available_tickets" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.available_tickets') }}
            </label>
            <input id="available_tickets" name="available_tickets" type="number" min="1"
                   value="{{ old('available_tickets', isset($event) ? $event->available_tickets : '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('available_tickets')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="description_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.description_ar') }}
            </label>
            <textarea id="description_ar" name="description_ar" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="rtl">{{ old('description_ar', isset($event) ? $event->description_ar : '') }}</textarea>
            @error('description_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="description_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.events.form.description_en') }}
            </label>
            <textarea id="description_en" name="description_en" rows="4"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="ltr">{{ old('description_en', isset($event) ? $event->description_en : '') }}</textarea>
            @error('description_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', isset($event) ? $event->is_active : true))
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm font-medium text-slate-600">{{ __('admin.events.form.is_active') }}</span>
        </label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('admin.events.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            <i class="fas fa-times"></i>
            {{ __('admin.events.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-save"></i>
            {{ $editing ? __('admin.events.actions.update') : __('admin.events.actions.store') }}
        </button>
    </div>
</div>

