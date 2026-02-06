@extends('layouts.admin')

@section('title', __('admin.users.manage_hotels'))
@section('page-title', __('admin.users.manage_hotels'))
@section('page-subtitle', __('admin.users.manage_hotels_subtitle', ['user' => $user->name]))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between mb-6">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.users.manage_hotels') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.users.manage_hotels_subtitle', ['user' => $user->name]) }}</p>
            </div>
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                <i class="fas fa-arrow-right"></i>
                {{ __('admin.users.back_to_list') }}
            </a>
        </div>

        <form method="POST" action="{{ route('admin.users.update-hotels', $user) }}">
            @csrf
            @method('PUT')

            <div class="grid gap-4">
                <div class="grid gap-2">
                    <label class="text-sm font-medium text-slate-600">
                        {{ __('admin.users.select_hotels') }}
                    </label>
                    <div class="grid gap-3 max-h-96 overflow-y-auto border border-slate-200 rounded-xl p-4">
                        @forelse ($allHotels as $hotel)
                            <label class="flex items-center gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer">
                                <input type="checkbox" name="hotels[]" value="{{ $hotel->id }}"
                                       @checked(in_array($hotel->id, $userHotels))
                                       class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                <div class="flex-1">
                                    <div class="font-semibold text-slate-800">
                                        {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ app()->getLocale() === 'ar' ? ($hotel->province->name_ar ?? '-') : ($hotel->province->name_en ?? '-') }}
                                    </div>
                                </div>
                                @if(in_array($hotel->id, $userHotels))
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.users.assigned') }}
                                    </span>
                                @endif
                            </label>
                        @empty
                            <p class="text-sm text-slate-500 text-center py-4">{{ __('admin.users.no_hotels_available') }}</p>
                        @endforelse
                    </div>
                    <p class="text-xs text-slate-400">{{ __('admin.users.hotels_selection_hint') }}</p>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        {{ __('admin.users.cancel') }}
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-400/40 transition hover:bg-indigo-700">
                        <i class="fas fa-save"></i>
                        {{ __('admin.users.save') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection

