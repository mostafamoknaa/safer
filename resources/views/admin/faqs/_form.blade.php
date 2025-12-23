@php
    $editing = isset($faq);
@endphp

<div class="grid gap-6">
    <div class="grid gap-2">
        <label for="question_ar" class="text-sm font-medium text-slate-600">
            {{ __('admin.faqs.form.question_ar') }}
        </label>
        <input id="question_ar" name="question_ar" type="text"
               value="{{ old('question_ar', $faq->question_ar ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
               required>
        @error('question_ar')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="question_en" class="text-sm font-medium text-slate-600">
            {{ __('admin.faqs.form.question_en') }}
        </label>
        <input id="question_en" name="question_en" type="text"
               value="{{ old('question_en', $faq->question_en ?? '') }}"
               class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
               required>
        @error('question_en')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="answer_ar" class="text-sm font-medium text-slate-600">
            {{ __('admin.faqs.form.answer_ar') }}
        </label>
        <input id="answer_ar" name="answer_ar" type="hidden" value="{{ old('answer_ar', $faq->answer_ar ?? '') }}">
        <trix-editor input="answer_ar" dir="rtl" class="bg-white"></trix-editor>
        @error('answer_ar')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2">
        <label for="answer_en" class="text-sm font-medium text-slate-600">
            {{ __('admin.faqs.form.answer_en') }}
        </label>
        <input id="answer_en" name="answer_en" type="hidden" value="{{ old('answer_en', $faq->answer_en ?? '') }}">
        <trix-editor input="answer_en" class="bg-white" dir="ltr"></trix-editor>
        @error('answer_en')
            <p class="text-xs text-rose-600">{{ $message }}</p>
        @enderror
    </div>

    <div class="grid gap-2 sm:grid-cols-2 sm:gap-4">
        <div class="grid gap-2">
            <label for="order_column" class="text-sm font-medium text-slate-600">
                {{ __('admin.faqs.form.order_column') }}
            </label>
            <input id="order_column" name="order_column" type="number" min="0"
                   value="{{ old('order_column', $faq->order_column ?? 0) }}"
                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
            <p class="text-xs text-slate-400">{{ __('admin.faqs.form.order_hint') }}</p>
            @error('order_column')
                <p class="text-xs text-rose-600">{{ $message }}</p>
            @enderror
        </div>
        <label class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 mt-8 sm:mt-0">
            <input type="checkbox" name="is_active" value="1"
                   @checked(old('is_active', $faq->is_active ?? true))
                   class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
            {{ __('admin.faqs.form.is_active') }}
        </label>
    </div>

    <div class="flex items-center justify-end gap-3">
        <a href="{{ route('admin.faqs.index') }}"
           class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
            {{ __('admin.faqs.actions.cancel') }}
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
            <i class="fas fa-floppy-disk"></i>
            {{ $editing ? __('admin.faqs.actions.update') : __('admin.faqs.actions.store') }}
        </button>
    </div>
</div>

