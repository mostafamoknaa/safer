@extends('layouts.admin')

@section('title', __('admin.bookings.page_title'))
@section('page-title', __('admin.bookings.heading'))
@section('page-subtitle', __('admin.bookings.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.bookings.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.bookings.subheading') }}</p>
            </div>
            <a href="{{ route('admin.bookings.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.bookings.actions.create') }}
            </a>
        </div>

        {{-- Search and Filter --}}
        <form method="GET" action="{{ route('admin.bookings.index') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('admin.bookings.search_placeholder') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                
                <select name="status" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">{{ __('admin.bookings.all_statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('admin.bookings.status.pending') }}</option>
                    <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>{{ __('admin.bookings.status.confirmed') }}</option>
                    <option value="checked_in" {{ request('status') === 'checked_in' ? 'selected' : '' }}>{{ __('admin.bookings.status.checked_in') }}</option>
                    <option value="checked_out" {{ request('status') === 'checked_out' ? 'selected' : '' }}>{{ __('admin.bookings.status.checked_out') }}</option>
                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>{{ __('admin.bookings.status.cancelled') }}</option>
                </select>

                <select name="hotel_id" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">{{ __('admin.bookings.all_hotels') }}</option>
                    @foreach($hotels as $hotel)
                        <option value="{{ $hotel->id }}" {{ request('hotel_id') == $hotel->id ? 'selected' : '' }}>
                            {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                        </option>
                    @endforeach
                </select>

                <select name="user_id" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">{{ __('admin.bookings.all_users') }}</option>
                    @foreach($users as $user)
                        <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('admin.bookings.search') }}
                </button>
                @if(request()->anyFilled(['search', 'status', 'hotel_id', 'user_id']))
                    <a href="{{ route('admin.bookings.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-times"></i>
                        {{ __('admin.bookings.clear_filters') }}
                    </a>
                @endif
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.reference') }}</th>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.user') }}</th>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.hotel') }}</th>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.check_in') }}</th>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.check_out') }}</th>
                        <th class="px-4 py-3">{{ __('admin.bookings.table.total_price') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.bookings.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.bookings.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($bookings as $booking)
                        <tr>
                            <td class="px-4 py-4 font-mono text-xs font-semibold text-slate-800">
                                {{ $booking->booking_reference }}
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <div class="font-semibold text-slate-800">{{ $booking->user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $booking->user->email }}</div>
                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ $booking->check_in_date->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ $booking->check_out_date->format('Y-m-d') }}
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-800">
                                {{ number_format($booking->total_price, 2) }} {{ __('admin.bookings.currency') }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-50 text-yellow-600',
                                        'confirmed' => 'bg-blue-50 text-blue-600',
                                        'checked_in' => 'bg-emerald-50 text-emerald-600',
                                        'checked_out' => 'bg-slate-100 text-slate-600',
                                        'cancelled' => 'bg-rose-50 text-rose-600',
                                    ];
                                    $statusClass = $statusClasses[$booking->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    <span class="h-2 w-2 rounded-full bg-current opacity-75"></span>
                                    {{ __('admin.bookings.status.' . $booking->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.bookings.show', $booking) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-eye"></i>
                                        {{ __('admin.bookings.actions.view') }}
                                    </a>
                                    <a href="{{ route('admin.bookings.edit', $booking) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.bookings.actions.edit') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.bookings.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

