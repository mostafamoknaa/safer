@extends('layouts.admin')

@section('title', __('admin.payments.create_title'))
@section('page-title', __('admin.payments.create_heading'))
@section('page-subtitle', __('admin.payments.create_subheading', ['reference' => $booking->booking_reference]))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        {{-- Booking Info --}}
        <div class="mb-6 rounded-xl border border-slate-200 bg-slate-50 p-4">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-slate-500">{{ __('admin.payments.form.booking') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ $booking->booking_reference }}</p>
                </div>
                <div class="text-left">
                    <p class="text-sm text-slate-500">{{ __('admin.bookings.table.total_price') }}</p>
                    <p class="text-lg font-bold text-slate-900">{{ number_format($booking->total_price, 2) }} {{ __('admin.payments.currency') }}</p>
                    <p class="text-sm text-slate-500">{{ __('admin.payments.remaining_amount') }}</p>
                    <p class="text-lg font-bold text-emerald-600">{{ number_format($booking->remaining_amount, 2) }} {{ __('admin.payments.currency') }}</p>
                </div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.payments.store') }}" class="space-y-6">
            @csrf
            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <label for="amount" class="text-sm font-medium text-slate-600">
                        {{ __('admin.payments.form.amount') }}
                    </label>
                    <input id="amount" name="amount" type="number" step="0.01" min="0.01" max="{{ $booking->remaining_amount }}"
                           value="{{ old('amount', $booking->remaining_amount) }}"
                           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                           required>
                    @error('amount')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                    <p class="text-xs text-slate-400">{{ __('admin.payments.form.amount_hint', ['max' => number_format($booking->remaining_amount, 2)]) }}</p>
                </div>

                <div class="grid gap-2">
                    <label for="payment_method" class="text-sm font-medium text-slate-600">
                        {{ __('admin.payments.form.payment_method') }}
                    </label>
                    <select id="payment_method" name="payment_method"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            required>
                        <option value="cash" @selected(old('payment_method', 'cash') == 'cash')>
                            {{ __('admin.payments.payment_method.cash') }}
                        </option>
                        <option value="card" @selected(old('payment_method') == 'card')>
                            {{ __('admin.payments.payment_method.card') }}
                        </option>
                        <option value="bank_transfer" @selected(old('payment_method') == 'bank_transfer')>
                            {{ __('admin.payments.payment_method.bank_transfer') }}
                        </option>
                        <option value="online" @selected(old('payment_method') == 'online')>
                            {{ __('admin.payments.payment_method.online') }}
                        </option>
                        <option value="other" @selected(old('payment_method') == 'other')>
                            {{ __('admin.payments.payment_method.other') }}
                        </option>
                    </select>
                    @error('payment_method')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div class="grid gap-2">
                    <label for="status" class="text-sm font-medium text-slate-600">
                        {{ __('admin.payments.form.status') }}
                    </label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                            required>
                        <option value="pending" @selected(old('status', 'pending') == 'pending')>
                            {{ __('admin.payments.status.pending') }}
                        </option>
                        <option value="completed" @selected(old('status') == 'completed')>
                            {{ __('admin.payments.status.completed') }}
                        </option>
                        <option value="failed" @selected(old('status') == 'failed')>
                            {{ __('admin.payments.status.failed') }}
                        </option>
                        <option value="refunded" @selected(old('status') == 'refunded')>
                            {{ __('admin.payments.status.refunded') }}
                        </option>
                    </select>
                    @error('status')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid gap-2">
                    <label for="transaction_id" class="text-sm font-medium text-slate-600">
                        {{ __('admin.payments.form.transaction_id') }}
                    </label>
                    <input id="transaction_id" name="transaction_id" type="text"
                           value="{{ old('transaction_id') }}"
                           class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    @error('transaction_id')
                        <p class="text-xs text-rose-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid gap-2">
                <label for="notes" class="text-sm font-medium text-slate-600">
                    {{ __('admin.payments.form.notes') }}
                </label>
                <textarea id="notes" name="notes" rows="3"
                          class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">{{ old('notes') }}</textarea>
                @error('notes')
                    <p class="text-xs text-rose-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <a href="{{ route('admin.bookings.show', $booking) }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    <i class="fas fa-times"></i>
                    {{ __('admin.bookings.actions.cancel') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-save"></i>
                    {{ __('admin.payments.messages.created') }}
                </button>
            </div>
        </form>
    </div>
@endsection

