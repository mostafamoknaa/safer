@php
    $editing = isset($policy);
@endphp

<div class="grid gap-6">
    <div class="grid gap-2">
        <label for="title_ar" class="text-sm font-medium text-slate-600">
            {{ __('admin.policies.form.title_ar') }}
        </label>
        <input id="title_ar" name="title_ar" type="text"
               value="{{ old('title_ar', $policy->title_ar ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
               required>
        @error('title_ar')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="title_en" class="text-sm font-medium text-slate-600">
            {{ __('admin.policies.form.title_en') }}
        </label>
        <input id="title_en" name="title_en" type="text"
               value="{{ old('title_en', $policy->title_en ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
               required>
        @error('title_en')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2 sm:grid-cols-2 sm:gap-4">
        <div class="grid gap-2">
            <label for="slug" class="text-sm font-medium text-slate-600">
                {{ __('admin.policies.form.slug') }}
            </label>
            <input id="slug" name="slug" type="text"
                   value="{{ old('slug', $policy->slug ?? '') }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                   placeholder="privacy-policy">
            <p class="text-xs text-slate-400">{{ __('admin.policies.form.slug_hint') }}</p>
            @error('slug')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 mt-8 sm:mt-0">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $policy->is_active ?? true))
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            {{ __('admin.policies.form.is_active') }}
        </label>
    </div>

    <div class="grid gap-2">
        <label for="body_ar" class="text-sm font-medium text-slate-600">
            {{ __('admin.policies.form.body_ar') }}
        </label>
        <input id="body_ar" name="body_ar" type="hidden" value="{{ old('body_ar', $policy->body_ar ?? '') }}">
        <trix-editor input="body_ar" dir="rtl" class="bg-white"></trix-editor>
        @error('body_ar')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="body_en" class="text-sm font-medium text-slate-600">
            {{ __('admin.policies.form.body_en') }}
        </label>
        <input id="body_en" name="body_en" type="hidden" value="{{ old('body_en', $policy->body_en ?? '') }}">
        <trix-editor input="body_en" class="bg-white" dir="ltr"></trix-editor>
        @error('body_en')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.policies.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('admin.policies.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('admin.policies.actions.update') : __('admin.policies.actions.store') }}
        </button>
    </div>
</div>

