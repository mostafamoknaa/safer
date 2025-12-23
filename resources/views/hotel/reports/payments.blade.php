@extends('layouts.hotel')

@section('title', __('hotel.reports.payments.page_title'))
@section('page-title', __('hotel.reports.payments.heading'))
@section('page-subtitle', __('hotel.reports.payments.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/95 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('hotel.reports.payments.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('hotel.reports.payments.subheading') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('hotel.reports.payments') }}">
                    @foreach(request()->except('export') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" name="export" value="csv"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100">
                        <i class="fas fa-file-csv"></i>
                        {{ __('hotel.reports.payments.actions.download_csv') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('hotel.reports.payments') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="grid gap-2">
                    <label for="from_date" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.payments.filters.from_date') }}
                    </label>
                    <input id="from_date" name="from_date" type="date"
                           value="{{ request('from_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="to_date" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.payments.filters.to_date') }}
                    </label>
                    <input id="to_date" name="to_date" type="date"
                           value="{{ request('to_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="status" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.payments.filters.status') }}
                    </label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('hotel.reports.payments.filters.all_statuses') }}</option>
                        <option value="pending" @selected(request('status') === 'pending')>{{ __('hotel.payments.status.pending') }}</option>
                        <option value="completed" @selected(request('status') === 'completed')>{{ __('hotel.payments.status.completed') }}</option>
                        <option value="failed" @selected(request('status') === 'failed')>{{ __('hotel.payments.status.failed') }}</option>
                        <option value="refunded" @selected(request('status') === 'refunded')>{{ __('hotel.payments.status.refunded') }}</option>
                    </select>
                </div>

                <div class="grid gap-2">
                    <label for="method" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.payments.filters.method') }}
                    </label>
                    <select id="method" name="method"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('hotel.reports.payments.filters.all_methods') }}</option>
                        <option value="cash" @selected(request('method') === 'cash')>{{ __('hotel.payments.payment_method.cash') }}</option>
                        <option value="card" @selected(request('method') === 'card')>{{ __('hotel.payments.payment_method.card') }}</option>
                        <option value="bank_transfer" @selected(request('method') === 'bank_transfer')>{{ __('hotel.payments.payment_method.bank_transfer') }}</option>
                        <option value="online" @selected(request('method') === 'online')>{{ __('hotel.payments.payment_method.online') }}</option>
                        <option value="other" @selected(request('method') === 'other')>{{ __('hotel.payments.payment_method.other') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 mt-4">
                <div class="grid gap-2">
                    <label for="hotel_id" class="text-xs font-medium text-slate-500">
                        {{ __('hotel.reports.payments.filters.hotel') }}
                    </label>
                    <select id="hotel_id" name="hotel_id"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('hotel.reports.payments.filters.all_hotels') }}</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(request('hotel_id') == $hotel->id)>
                                {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-4">
                <a href="{{ route('hotel.reports.payments') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    <i class="fas fa-rotate"></i>
                    {{ __('hotel.reports.payments.actions.reset') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('hotel.reports.payments.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                <tr>
                    <th class="px-4 py-3">{{ __('hotel.payments.table.booking') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.reports.payments.table.user') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.reports.payments.table.hotel') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.payments.table.amount') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.payments.table.payment_method') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.payments.table.status') }}</th>
                    <th class="px-4 py-3">{{ __('hotel.payments.table.paid_at') }}</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                @forelse($payments as $payment)
                    <tr>
                        <td class="px-4 py-3 font-mono text-xs text-slate-600">
                            {{ $payment->booking?->booking_reference ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-800">
                            {{ $payment->booking?->user?->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ $payment->booking?->hotel ? (app()->getLocale() === 'ar' ? $payment->booking->hotel->name_ar : $payment->booking->hotel->name_en) : '-' }}
                        </td>
                        <td class="px-4 py-3 font-semibold text-slate-900">
                            {{ number_format($payment->amount, 2) }} {{ __('hotel.payments.currency') }}
                        </td>
                        <td class="px-4 py-3 text-slate-700">
                            {{ __('hotel.payments.payment_method.' . $payment->payment_method) }}
                        </td>
                        <td class="px-4 py-3">
                            @include('admin.payments._status_badge', ['status' => $payment->status])
                        </td>
                        <td class="px-4 py-3 text-xs text-slate-500">
                            {{ optional($payment->paid_at)->format('Y-m-d H:i') ?? '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                            {{ __('hotel.reports.payments.empty') }}
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->links() }}
        </div>
    </div>
@endsection


