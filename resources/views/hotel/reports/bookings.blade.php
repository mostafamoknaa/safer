@extends('layouts.hotel')

@section('title', __('hotel.reports.bookings.page_title'))
@section('page-title', __('hotel.reports.bookings.heading'))
@section('page-subtitle', __('hotel.reports.bookings.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('hotel.reports.bookings.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('hotel.reports.bookings.subheading') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('hotel.reports.bookings') }}">
                    @foreach(request()->except('export') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" name="export" value="csv"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100">
                        <i class="fas fa-file-csv"></i>
                        {{ __('hotel.reports.bookings.actions.download_csv') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('hotel.reports.bookings') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="grid gap-2">
                    <label for="from_date" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.bookings.filters.from_date') }}
                    </label>
                    <input id="from_date" name="from_date" type="date"
                           value="{{ request('from_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="to_date" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.bookings.filters.to_date') }}
                    </label>
                    <input id="to_date" name="to_date" type="date"
                           value="{{ request('to_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="status" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.bookings.filters.status') }}
                    </label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('hotel.reports.bookings.filters.all_statuses') }}</option>
                        <option value="pending" @selected(request('status') === 'pending')>{{ __('hotel.bookings.status.pending') }}</option>
                        <option value="confirmed" @selected(request('status') === 'confirmed')>{{ __('hotel.bookings.status.confirmed') }}</option>
                        <option value="checked_in" @selected(request('status') === 'checked_in')>{{ __('hotel.bookings.status.checked_in') }}</option>
                        <option value="checked_out" @selected(request('status') === 'checked_out')>{{ __('hotel.bookings.status.checked_out') }}</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('hotel.bookings.status.cancelled') }}</option>
                    </select>
                </div>

                <div class="grid gap-2">
                    <label for="hotel_id" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.bookings.filters.hotel') }}
                    </label>
                    <select id="hotel_id" name="hotel_id"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('hotel.reports.bookings.filters.all_hotels') }}</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(request('hotel_id') == $hotel->id)>
                                {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('hotel.reports.bookings') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    <i class="fas fa-rotate"></i>
                    {{ __('hotel.reports.bookings.actions.reset') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('hotel.reports.bookings.actions.filter') }}
                </button>
            </div>
        </form>

        {{-- Table --}}
        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.reference') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.user') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.hotel') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.room') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.check_in') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.check_out') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.status') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.bookings.table.total_price') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.reports.bookings.table.created_at') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($bookings as $booking)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">
                            {{ $booking->booking_reference }}
                        </td>
                        <td class="px-4 py-3 text-slate-800">
                            {{ $booking->user?->name }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $booking->hotel ? (app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ $booking->room_id ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ optional($booking->check_in_date)->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3 text-slate-600">
                            {{ optional($booking->check_out_date)->format('Y-m-d') }}
                        </td>
                        <td class="px-4 py-3">
                            @include('admin.bookings._status_badge', ['status' => $booking->status])
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">
                            {{ number_format($booking->total_price, 2) }} {{ __('hotel.bookings.currency') }}
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ optional($booking->created_at)->format('Y-m-d H:i') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="px-4 py-6 text-center text-sm text-slate-500">
                            {{ __('hotel.reports.bookings.empty') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    </div>
@endsection


