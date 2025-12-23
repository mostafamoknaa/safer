@extends('layouts.hotel')

@section('title', __('hotel.payments.page_title'))
@section('page-title', __('hotel.payments.heading'))
@section('page-subtitle', __('hotel.payments.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('hotel.payments.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('hotel.payments.subheading') }}</p>
            </div>
        </div>

        {{-- Search and Filter --}}
        <form method="GET" action="{{ route('hotel.payments.index') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 sm:grid-cols-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('hotel.payments.search_placeholder') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                
                <select name="status" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">{{ __('hotel.payments.all_statuses') }}</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>{{ __('hotel.payments.status.pending') }}</option>
                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>{{ __('hotel.payments.status.completed') }}</option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>{{ __('hotel.payments.status.failed') }}</option>
                    <option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>{{ __('hotel.payments.status.refunded') }}</option>
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('hotel.payments.search') }}
                </button>
                @if(request()->anyFilled(['search', 'status']))
                    <a href="{{ route('hotel.payments.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-times"></i>
                        {{ __('hotel.bookings.clear_filters') }}
                    </a>
                @endif
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('hotel.payments.table.id') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.payments.table.booking') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.payments.table.amount') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.payments.table.payment_method') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.payments.table.status') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.payments.table.paid_at') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.payments.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($payments as $payment)
                        <tr>
                            <td class="px-4 py-4 font-mono text-xs font-semibold text-slate-800">
                                #{{ $payment->id }}
                            </td>
                            <td class="px-4 py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $payment->booking->booking_reference }}</p>
                                    <p class="text-xs text-slate-500">{{ $payment->booking->user->name }}</p>
                                </div>
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-900">
                                {{ number_format($payment->amount, 2) }} {{ __('hotel.payments.currency') }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ __('hotel.payments.payment_method.' . $payment->payment_method) }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-50 text-yellow-600',
                                        'completed' => 'bg-emerald-50 text-emerald-600',
                                        'failed' => 'bg-rose-50 text-rose-600',
                                        'refunded' => 'bg-slate-100 text-slate-600',
                                    ];
                                    $statusClass = $statusClasses[$payment->status] ?? 'bg-slate-100 text-slate-600';
                                @endphp
                                <span class="inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                                    {{ __('hotel.payments.status.' . $payment->status) }}
                                </span>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-' }}
                            </td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('hotel.payments.show', $payment) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                    <i class="fas fa-eye"></i>
                                    {{ __('hotel.payments.show_title') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('hotel.payments.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $payments->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

