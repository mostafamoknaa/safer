@extends('layouts.admin')

@section('title', __('admin.service_requests.edit_title'))
@section('page-title', __('admin.service_requests.edit_heading'))
@section('page-subtitle', __('admin.service_requests.edit_subheading'))

@section('content')
    <form method="POST" action="{{ route('admin.service-requests.update', $serviceRequest) }}"
          class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur space-y-6">
        @csrf
        @method('PUT')

        <div class="grid gap-6">
            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <label for="status" class="text-sm font-medium text-slate-600">
                        {{ __('admin.service_requests.form.status') }}
                    </label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            required>
                        <option value="pending" {{ old('status', $serviceRequest->status) === 'pending' ? 'selected' : '' }}>
                            {{ __('admin.service_requests.badges.pending') }}
                        </option>
                        <option value="confirmed" {{ old('status', $serviceRequest->status) === 'confirmed' ? 'selected' : '' }}>
                            {{ __('admin.service_requests.badges.confirmed') }}
                        </option>
                        <option value="in_progress" {{ old('status', $serviceRequest->status) === 'in_progress' ? 'selected' : '' }}>
                            {{ __('admin.service_requests.badges.in_progress') }}
                        </option>
                        <option value="completed" {{ old('status', $serviceRequest->status) === 'completed' ? 'selected' : '' }}>
                            {{ __('admin.service_requests.badges.completed') }}
                        </option>
                        <option value="cancelled" {{ old('status', $serviceRequest->status) === 'cancelled' ? 'selected' : '' }}>
                            {{ __('admin.service_requests.badges.cancelled') }}
                        </option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-2">
                <label for="notes" class="text-sm font-medium text-slate-600">
                    {{ __('admin.service_requests.form.notes') }}
                </label>
                <textarea id="notes" name="notes" rows="4"
                          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('notes', $serviceRequest->notes ?? '') }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
            <a href="{{ route('admin.service-requests.show', $serviceRequest) }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                <i class="fas fa-times"></i>
                {{ __('admin.service_requests.actions.cancel') }}
            </a>
            <button type="submit"
                    class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-save"></i>
                {{ __('admin.service_requests.actions.update') }}
            </button>
        </div>
    </form>
@endsection

