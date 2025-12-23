@extends('layouts.admin')

@section('title', __('admin.bookings.show_title'))
@section('page-title', __('admin.bookings.show_heading', ['reference' => $booking->booking_reference]))
@section('page-subtitle', __('admin.bookings.show_subheading'))

@section('content')
    <div class="grid gap-6">
        {{-- Booking Info Card --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $booking->booking_reference }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('admin.bookings.show_subheading') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.bookings.edit', $booking) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        <i class="fas fa-edit"></i>
                        {{ __('admin.bookings.actions.edit') }}
                    </a>
                    <a href="{{ route('admin.bookings.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('admin.bookings.actions.back') }}
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.user') }}</label>
                        <div class="mt-1 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $booking->user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $booking->user->email }}</p>
                                @if($booking->user->phone)
                                    <p class="text-sm text-slate-500">{{ $booking->user->phone }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.hotel') }}</label>
                        <p class="mt-1 font-semibold text-slate-900">{{ app()->getLocale() === 'ar' ? $booking->hotel->name_ar : $booking->hotel->name_en }}</p>
                    </div>

                    @if($booking->room)
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.room') }}</label>
                            <p class="mt-1 text-slate-700">{{ number_format($booking->room->price_per_night, 2) }} {{ __('admin.bookings.currency') }}</p>
                        </div>
                    @endif
                </div>

                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.check_in') }}</label>
                            <p class="mt-1 font-semibold text-slate-900">{{ $booking->check_in_date->format('Y-m-d') }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.check_out') }}</label>
                            <p class="mt-1 font-semibold text-slate-900">{{ $booking->check_out_date->format('Y-m-d') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.guests') }}</label>
                            <p class="mt-1 text-slate-700">{{ $booking->guests_count }}</p>
                        </div>
                        <div>
                            <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.rooms') }}</label>
                            <p class="mt-1 text-slate-700">{{ $booking->rooms_count }}</p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.nights') }}</label>
                        <p class="mt-1 text-slate-700">{{ $booking->nights_count }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.status') }}</label>
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
                        <span class="mt-1 inline-flex items-center gap-1 rounded-full px-3 py-1 text-xs font-semibold {{ $statusClass }}">
                            <span class="h-2 w-2 rounded-full bg-current opacity-75"></span>
                            {{ __('admin.bookings.status.' . $booking->status) }}
                        </span>
                    </div>
                </div>
            </div>

            @if($booking->notes)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.form.notes') }}</label>
                    <p class="mt-1 text-slate-700 whitespace-pre-wrap">{{ $booking->notes }}</p>
                </div>
            @endif

            @if($booking->admin_notes)
                <div class="mt-4">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.form.admin_notes') }}</label>
                    <p class="mt-1 text-slate-700 whitespace-pre-wrap">{{ $booking->admin_notes }}</p>
                </div>
            @endif
        </div>

        {{-- Payment Summary --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-slate-900">{{ __('admin.payments.heading') }}</h3>
                <a href="{{ route('admin.bookings.payments.create', $booking) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-emerald-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-700">
                    <i class="fas fa-plus"></i>
                    {{ __('admin.payments.create_title') }}
                </a>
            </div>

            <div class="grid gap-4 md:grid-cols-3 mb-6">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.bookings.table.total_price') }}</label>
                    <p class="mt-1 text-2xl font-bold text-slate-900">{{ number_format($booking->total_price, 2) }} {{ __('admin.bookings.currency') }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-blue-50 p-4">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.total_paid') }}</label>
                    <p class="mt-1 text-2xl font-bold text-blue-600">{{ number_format($booking->total_paid, 2) }} {{ __('admin.bookings.currency') }}</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-emerald-50 p-4">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.payments.remaining_amount') }}</label>
                    <p class="mt-1 text-2xl font-bold text-emerald-600">{{ number_format($booking->remaining_amount, 2) }} {{ __('admin.bookings.currency') }}</p>
                </div>
            </div>

            {{-- Payments List --}}
            @if($booking->payments->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">{{ __('admin.payments.table.amount') }}</th>
                                <th class="px-4 py-3">{{ __('admin.payments.table.payment_method') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('admin.payments.table.status') }}</th>
                                <th class="px-4 py-3">{{ __('admin.payments.table.paid_at') }}</th>
                                <th class="px-4 py-3 text-center">{{ __('admin.payments.table.actions') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($booking->payments as $payment)
                                <tr>
                                    <td class="px-4 py-4 font-semibold text-slate-900">
                                        {{ number_format($payment->amount, 2) }} {{ __('admin.payments.currency') }}
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ __('admin.payments.payment_method.' . $payment->payment_method) }}
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
                                            {{ __('admin.payments.status.' . $payment->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-4 text-slate-600">
                                        {{ $payment->paid_at ? $payment->paid_at->format('Y-m-d H:i') : '-' }}
                                    </td>
                                    <td class="px-4 py-4 text-center">
                                        <a href="{{ route('admin.payments.show', $payment) }}"
                                           class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                            <i class="fas fa-eye"></i>
                                            {{ __('admin.payments.show_title') }}
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-12 text-slate-500">
                    <i class="fas fa-credit-card text-4xl mb-3 opacity-50"></i>
                    <p>{{ __('admin.payments.empty') }}</p>
                </div>
            @endif
        </div>
    </div>
@endsection

