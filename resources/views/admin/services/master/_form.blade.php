<div class="space-y-6">
    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        {{-- Name Arabic --}}
        <div>
            <label for="name_ar" class="block text-sm font-medium text-slate-700">{{ __('admin.services.form.name_ar') }}</label>
            <input type="text" name="name_ar" id="name_ar" value="{{ old('name_ar', $service->name_ar ?? '') }}"
                   class="mt-1 block w-full rounded-xl border-black-700 bg-slate-50 shadow-sm focus:border-slate-900 focus:ring-slate-900 sm:text-sm @error('name_ar') border-rose-500 @enderror"
                   required>
            @error('name_ar')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>

        {{-- Name English --}}
        <div>
            <label for="name_en" class="block text-sm font-medium text-slate-700">{{ __('admin.services.form.name_en') }}</label>
            <input type="text" name="name_en" id="name_en" value="{{ old('name_en', $service->name_en ?? '') }}"
                   class="mt-1 block w-full rounded-xl border-slate-200 bg-slate-50 shadow-sm focus:border-slate-900 focus:ring-slate-900 sm:text-sm @error('name_en') border-rose-500 @enderror"
                   required>
            @error('name_en')
                <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
            @enderror
        </div>
    </div>

    {{-- Audio / Image upload --}}
    <div>
        <label for="image" class="block text-sm font-medium text-slate-700">{{ __('admin.services.form.image') }}</label>
        <div class="mt-1 flex items-center gap-4">
            @if(isset($service) && $service->image)
                <img src="{{ asset('storage/' . $service->image) }}" alt="" class="h-20 w-20 rounded-xl object-cover shadow-md">
            @endif
            <div class="flex-1">
                <input type="file" name="image" id="image"
                       class="block w-full text-sm text-slate-500 file:mr-4 file:rounded-xl file:border-0 file:bg-slate-900 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-slate-700">
                <p class="mt-1 text-xs text-slate-500">{{ __('admin.services.form.image_hint') }}</p>
            </div>
        </div>
        @error('image')
            <p class="mt-1 text-xs text-rose-500">{{ $message }}</p>
        @enderror
    </div>

    {{-- Active Status --}}
    <div class="flex items-center gap-2">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" id="is_active" value="1"
               @checked(old('is_active', $service->is_active ?? true))
               class="h-5 w-5 rounded border-slate-300 text-slate-900 focus:ring-slate-900">
        <label for="is_active" class="text-sm font-medium text-slate-700">{{ __('admin.services.form.is_active') }}</label>
    </div>
</div>
