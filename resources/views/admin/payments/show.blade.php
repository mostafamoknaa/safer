@extends('layouts.admin')

@section('title', __('admin.payments.show_title'))
@section('page-title', __('admin.payments.show_heading', ['id' => $payment->id]))
@section('page-subtitle', __('admin.payments.show_subheading'))

@section('content')
    <div class="grid gap-6">
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">#{{ $payment->id }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('admin.payments.show_subheading') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.show', $payment->booking) }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('admin.bookings.show_title') }}
                    </a>
                    <a href="{{ route('admin.payments.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-list"></i>
                        {{ __('admin.payments.heading') }}
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.booking') }}</label>
                        <div class="mt-1">
                            <p class="font-semibold text-slate-900">{{ $payment->booking->booking_reference }}</p>
                            <p class="text-sm text-slate-500">{{ $payment->booking->user->name }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.amount') }}</label>
                        <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($payment->amount, 2) }} {{ __('admin.payments.currency') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.payment_method') }}</label>
                        <p class="mt-1 font-semibold text-slate-900">{{ __('admin.payments.payment_method.' . $payment->payment_method) }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.status') }}</label>
                        @php
                            $statusClasses = [
                                'pending' => 'bg-yellow-50 text-yellow-600',
                                'completed' => 'bg-emerald-50 text-emerald-600',
                                'failed' => 'bg-rose-50 text-rose-600',
                                'refunded' => 'bg-slate-100 text-slate-600',
                            ];
                            $statusClass = $statusClasses[$payment->status] ?? 'bg-slate-100 text-slate-600';
                        @endphp
                        <span class="mt-1 inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            {{ __('admin.payments.status.' . $payment->status) }}
                        </span>
                    </div>

                    @if($payment->transaction_id)
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.transaction_id') }}</label>
                            <p class="mt-1 font-mono text-sm text-slate-900">{{ $payment->transaction_id }}</p>
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.table.paid_at') }}</label>
                        <p class="mt-1 text-slate-700">{{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-' }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.form.created_at') }}</label>
                        <p class="mt-1 text-slate-700">{{ $payment->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>
            </div>

            @if($payment->notes)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.form.notes') }}</label>
                    <p class="mt-1 text-slate-700 whitespace-pre-wrap">{{ $payment->notes }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

