@php
    $editing = isset($privateCar);
@endphp

<div class="grid gap-6">
    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="name_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.name_ar') }}
            </label>
            <input id="name_ar" name="name_ar" type="text"
                   value="{{ old('name_ar', $privateCar->name_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   dir="rtl"
                   required>
            @error('name_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="name_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.name_en') }}
            </label>
            <input id="name_en" name="name_en" type="text"
                   value="{{ old('name_en', $privateCar->name_en ?? '') }}"
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
            <label for="price" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.price') }}
            </label>
            <input id="price" name="price" type="number" step="0.01" min="0"
                   value="{{ old('price', $privateCar->price ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('price')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="seats_count" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.seats_count') }}
            </label>
            <input id="seats_count" name="seats_count" type="number" min="1"
                   value="{{ old('seats_count', $privateCar->seats_count ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   required>
            @error('seats_count')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="image" class="text-sm font-medium text-slate-600">
            {{ __('admin.private_cars.form.image') }}
        </label>
        <input id="image" name="image" type="file" accept="image/*"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
        @error('image')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
        @if($editing && $privateCar->image)
            <div class="mt-2">
                <img src="{{ asset('storage/' . $privateCar->image) }}" alt="{{ $privateCar->name }}" class="h-32 w-32 rounded-lg object-cover">
            </div>
        @endif
    </div>

    <div class="grid gap-4 sm:grid-cols-3">
        <div class="grid gap-2">
            <label for="max_speed" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.max_speed') }}
            </label>
            <input id="max_speed" name="max_speed" type="number" min="0"
                   value="{{ old('max_speed', $privateCar->max_speed ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @error('max_speed')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="acceleration" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.acceleration') }}
            </label>
            <input id="acceleration" name="acceleration" type="number" step="0.01" min="0"
                   value="{{ old('acceleration', $privateCar->acceleration ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @error('acceleration')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="power" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.power') }}
            </label>
            <input id="power" name="power" type="number" min="0"
                   value="{{ old('power', $privateCar->power ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            @error('power')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-4 sm:grid-cols-2">
        <div class="grid gap-2">
            <label for="notes_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.notes_ar') }}
            </label>
            <textarea id="notes_ar" name="notes_ar" rows="3"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="rtl">{{ old('notes_ar', $privateCar->notes_ar ?? '') }}</textarea>
            @error('notes_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="grid gap-2">
            <label for="notes_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.private_cars.form.notes_en') }}
            </label>
            <textarea id="notes_en" name="notes_en" rows="3"
                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                      dir="ltr">{{ old('notes_en', $privateCar->notes_en ?? '') }}</textarea>
            @error('notes_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $privateCar->is_active ?? true))
                   class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            <span class="text-sm font-medium text-slate-600">{{ __('admin.private_cars.form.is_active') }}</span>
        </label>
    </div>

    <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
        <a href="{{ route('admin.private-cars.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            <i class="fas fa-times"></i>
            {{ __('admin.private_cars.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-save"></i>
            {{ $editing ? __('admin.private_cars.actions.update') : __('admin.private_cars.actions.store') }}
        </button>
    </div>
</div>

