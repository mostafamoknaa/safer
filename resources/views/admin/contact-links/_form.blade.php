@php
    $editing = isset($contactLink);
@endphp

<div class="grid gap-6">
    <div class="grid gap-2">
        <label for="type" class="text-sm font-medium text-slate-600">
            {{ __('admin.contact_links.form.type') }}
        </label>
        <input id="type" name="type" type="text"
               value="{{ old('type', $contactLink->type ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
               required>
        @error('type')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2 sm:grid-cols-2 sm:gap-4">
        <div class="grid gap-2">
            <label for="title_ar" class="text-sm font-medium text-slate-600">
                {{ __('admin.contact_links.form.title_ar') }}
            </label>
            <input id="title_ar" name="title_ar" type="text"
                   value="{{ old('title_ar', $contactLink->title_ar ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
                   required>
            @error('title_ar')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <div class="grid gap-2">
            <label for="title_en" class="text-sm font-medium text-slate-600">
                {{ __('admin.contact_links.form.title_en') }}
            </label>
            <input id="title_en" name="title_en" type="text"
                   value="{{ old('title_en', $contactLink->title_en ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
                   required>
            @error('title_en')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <div class="grid gap-2">
        <label for="url" class="text-sm font-medium text-slate-600">
            {{ __('admin.contact_links.form.url') }}
        </label>
        <input id="url" name="url" type="url"
               value="{{ old('url', $contactLink->url ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
               required>
        @error('url')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="icon" class="text-sm font-medium text-slate-600">
            {{ __('admin.contact_links.form.icon') }}
        </label>
        <input id="icon" name="icon" type="text"
               value="{{ old('icon', $contactLink->icon ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-sky-400 focus:outline-none focus:ring-2 focus:ring-sky-200"
               placeholder="fas fa-link">
        @error('icon')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600">
        <input type="checkbox" name="is_active" value="1"
               @checked(old('is_active', $contactLink->is_active ?? true))
               class="h-4 w-4 rounded border-slate-300 text-sky-600 focus:ring-sky-500">
        {{ __('admin.contact_links.form.is_active') }}
    </label>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.contact-links.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('admin.contact_links.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('admin.contact_links.actions.update') : __('admin.contact_links.actions.store') }}
        </button>
    </div>
</div>

