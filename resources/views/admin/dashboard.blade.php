@extends('layouts.admin')

@section('title', __('admin.dashboard.page_title'))
@section('page-title', __('admin.dashboard.heading'))
@section('page-subtitle', __('admin.dashboard.subheading'))

@section('breadcrumb')
    <ol class="flex items-center gap-2">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="text-indigo-600 hover:text-indigo-700">
                {{ __('admin.dashboard.breadcrumb.home') }}
            </a>
        </li>
        <li aria-hidden="true">/</li>
        <li class="text-slate-500">{{ __('admin.dashboard.breadcrumb.current') }}</li>
    </ol>
@endsection

@section('content')
    <section class="grid gap-6 md:grid-cols-2 xl:grid-cols-4">
        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <div class="text-sm font-medium text-slate-500">{{ __('admin.dashboard.stats.total_bookings') }}</div>
            <div class="mt-4 flex items-end justify-between">
                <p class="text-3xl font-semibold text-slate-900">{{ number_format($totalBookings) }}</p>
                <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.stats.trend_up', ['value' => '']) }}</span>
            </div>
            <p class="mt-3 text-xs text-slate-400">{{ __('admin.dashboard.stats.updated') }}</p>
        </article>
        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <div class="text-sm font-medium text-slate-500">{{ __('admin.dashboard.stats.occupied_rooms') }}</div>
            <div class="mt-4 flex items-end justify-between">
                <p class="text-3xl font-semibold text-slate-900">{{ $occupancyRate }}%</p>
                <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.stats.trend_up', ['value' => '']) }}</span>
            </div>
            <p class="mt-3 text-xs text-slate-400">{{ __('admin.dashboard.stats.updated') }}</p>
        </article>
        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <div class="text-sm font-medium text-slate-500">{{ __('admin.dashboard.stats.new_clients') }}</div>
            <div class="mt-4 flex items-end justify-between">
                <p class="text-3xl font-semibold text-slate-900">{{ number_format($newClientsLast30) }}</p>
                <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.stats.trend_up', ['value' => '']) }}</span>
            </div>
            <p class="mt-3 text-xs text-slate-400">{{ __('admin.dashboard.stats.updated') }}</p>
        </article>
        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <div class="text-sm font-medium text-slate-500">{{ __('admin.dashboard.stats.revenue') }}</div>
            <div class="mt-4 flex items-end justify-between">
                <p class="text-3xl font-semibold text-slate-900">
                    {{ number_format($totalRevenue, 2) }} {{ __('admin.payments.currency') }}
                </p>
                <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.stats.trend_up', ['value' => '']) }}</span>
            </div>
            <p class="mt-3 text-xs text-slate-400">{{ __('admin.dashboard.stats.updated') }}</p>
        </article>
    </section>

    <section class="mt-8 grid gap-6 lg:grid-cols-3">
        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70 lg:col-span-2">
            <div class="flex items-center justify-between">
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.dashboard.activity.title') }}</h2>
                <span class="text-xs text-slate-400">{{ __('admin.dashboard.activity.subtitle') }}</span>
            </div>
            <ul class="mt-6 space-y-4">
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-indigo-500"></span>
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ __('admin.dashboard.activity.items.booking_created') }}</p>
                        <p class="text-xs text-slate-400">{{ __('admin.dashboard.activity.items.booking_created_meta') }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ __('admin.dashboard.activity.items.invoice_sent') }}</p>
                        <p class="text-xs text-slate-400">{{ __('admin.dashboard.activity.items.invoice_sent_meta') }}</p>
                    </div>
                </li>
                <li class="flex items-start gap-3">
                    <span class="mt-1 h-2.5 w-2.5 rounded-full bg-amber-500"></span>
                    <div>
                        <p class="text-sm font-medium text-slate-700">{{ __('admin.dashboard.activity.items.room_ready') }}</p>
                        <p class="text-xs text-slate-400">{{ __('admin.dashboard.activity.items.room_ready_meta') }}</p>
                    </div>
                </li>
            </ul>
        </article>

        <article class="rounded-xl bg-white p-6 shadow-sm ring-1 ring-slate-200/70">
            <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.dashboard.arrivals.title') }}</h2>
            <p class="mt-1 text-xs text-slate-400">{{ __('admin.dashboard.arrivals.subtitle') }}</p>
            <ul class="mt-5 space-y-3">
                <li class="rounded-lg border border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-800">
                        <span>{{ __('admin.dashboard.arrivals.guests.mohammed') }}</span>
                        <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.arrivals.status.confirmed') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">{{ __('admin.dashboard.arrivals.guests.mohammed_meta') }}</p>
                </li>
                <li class="rounded-lg border border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-800">
                        <span>{{ __('admin.dashboard.arrivals.guests.sara') }}</span>
                        <span class="text-xs font-medium text-amber-500">{{ __('admin.dashboard.arrivals.status.pending') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">{{ __('admin.dashboard.arrivals.guests.sara_meta') }}</p>
                </li>
                <li class="rounded-lg border border-slate-200 px-4 py-3">
                    <div class="flex items-center justify-between text-sm font-semibold text-slate-800">
                        <span>{{ __('admin.dashboard.arrivals.guests.layla') }}</span>
                        <span class="text-xs font-medium text-emerald-600">{{ __('admin.dashboard.arrivals.status.confirmed') }}</span>
                    </div>
                    <p class="mt-1 text-xs text-slate-500">{{ __('admin.dashboard.arrivals.guests.layla_meta') }}</p>
                </li>
            </ul>
        </article>
    </section>
@endsection

