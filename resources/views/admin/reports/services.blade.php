@extends('layouts.admin')

@section('title', __('admin.reports.services.page_title'))
@section('page-title', __('admin.reports.services.heading'))
@section('page-subtitle', __('admin.reports.services.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.reports.services.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.reports.services.subheading') }}</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <form method="GET" action="{{ route('admin.reports.services') }}">
                    @foreach(request()->except('export') as $key => $value)
                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                    @endforeach
                    <button type="submit" name="export" value="csv"
                            class="inline-flex items-center gap-2 rounded-xl bg-emerald-50 px-4 py-2 text-sm font-semibold text-emerald-700 shadow-sm hover:bg-emerald-100">
                        <i class="fas fa-file-csv"></i>
                        {{ __('admin.reports.services.actions.download_csv') }}
                    </button>
                </form>
            </div>
        </div>

        {{-- Quick navigation بين التقارير --}}
        <div class="mt-4 flex flex-wrap gap-2 text-xs">
            <a href="{{ route('admin.reports.bookings') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-200">
                <i class="fas fa-calendar-check"></i> {{ __('admin.reports.nav.bookings') }}
            </a>
            <a href="{{ route('admin.reports.payments') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-200">
                <i class="fas fa-credit-card"></i> {{ __('admin.reports.nav.payments') }}
            </a>
            <a href="{{ route('admin.reports.services') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-900 px-3 py-1.5 font-semibold text-white">
                <i class="fas fa-clipboard-list"></i> {{ __('admin.reports.nav.services') }}
            </a>
            <a href="{{ route('admin.reports.events') }}" class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1.5 font-semibold text-slate-700 hover:bg-slate-200">
                <i class="fas fa-calendar-alt"></i> {{ __('admin.reports.nav.events') }}
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.reports.services') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 md:grid-cols-4">
                <div class="grid gap-2">
                    <label for="from_date" class="text-xs font-medium text-slate-500">
                        {{ __('admin.reports.services.filters.from_date') }}
                    </label>
                    <input id="from_date" name="from_date" type="date"
                           value="{{ request('from_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="to_date" class="text-xs font-medium text-slate-500">
                        {{ __('admin.reports.services.filters.to_date') }}
                    </label>
                    <input id="to_date" name="to_date" type="date"
                           value="{{ request('to_date') }}"
                           class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                </div>

                <div class="grid gap-2">
                    <label for="service_type" class="text-xs font-medium text-slate-500">
                        {{ __('admin.reports.services.filters.service_type') }}
                    </label>
                    <select id="service_type" name="service_type"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('admin.reports.services.filters.all_types') }}</option>
                        <option value="bus" @selected(request('service_type') === 'bus')>{{ __('admin.service_requests.badges.bus') }}</option>
                        <option value="private_car" @selected(request('service_type') === 'private_car')>{{ __('admin.service_requests.badges.private_car') }}</option>
                    </select>
                </div>

                <div class="grid gap-2">
                    <label for="status" class="text-xs font-medium text-slate-500">
                        {{ __('admin.reports.services.filters.status') }}
                    </label>
                    <select id="status" name="status"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('admin.reports.services.filters.all_statuses') }}</option>
                        <option value="pending" @selected(request('status') === 'pending')>{{ __('admin.service_requests.badges.pending') }}</option>
                        <option value="confirmed" @selected(request('status') === 'confirmed')>{{ __('admin.service_requests.badges.confirmed') }}</option>
                        <option value="in_progress" @selected(request('status') === 'in_progress')>{{ __('admin.service_requests.badges.in_progress') }}</option>
                        <option value="completed" @selected(request('status') === 'completed')>{{ __('admin.service_requests.badges.completed') }}</option>
                        <option value="cancelled" @selected(request('status') === 'cancelled')>{{ __('admin.service_requests.badges.cancelled') }}</option>
                    </select>
                </div>
            </div>

            <div class="grid gap-4 md:grid-cols-2 mt-4">
                <div class="grid gap-2">
                    <label for="user_id" class="text-xs font-medium text-slate-500">
                        {{ __('admin.reports.services.filters.user') }}
                    </label>
                    <select id="user_id" name="user_id"
                            class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">{{ __('admin.reports.services.filters.all_users') }}</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" @selected(request('user_id') == $user->id)>
                                {{ $user->name }} ({{ $user->email }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex items-center justify-end gap-3 mt-4">
                <a href="{{ route('admin.reports.services') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                    <i class="fas fa-rotate"></i>
                    {{ __('admin.reports.services.actions.reset') }}
                </a>
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('admin.reports.services.actions.filter') }}
                </button>
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.reference') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.user') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.service_type') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.details') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.price') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.status') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.date') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($requests as $request)
                        <tr>
                            <td class="px-4 py-3 font-mono text-xs text-slate-600">{{ $request->request_reference }}</td>
                            <td class="px-4 py-3 text-slate-800">{{ $request->user?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                @if($request->service_type === 'bus')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ __('admin.service_requests.badges.bus') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-600">
                                        {{ __('admin.service_requests.badges.private_car') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-slate-600 text-xs">
                                @if($request->service_type === 'bus')
                                    {{ $request->departure_location }} → {{ $request->arrival_location }}
                                @else
                                    {{ $request->privateCar ? (app()->getLocale() === 'ar' ? $request->privateCar->name_ar : $request->privateCar->name_en) : '-' }}
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold text-slate-900">
                                {{ number_format($request->total_price, 2) }} {{ __('admin.service_requests.currency') }}
                            </td>
                            <td class="px-4 py-3">
                                @include('admin.service-requests._status_badge', ['status' => $request->status])
                            </td>
                            <td class="px-4 py-3 text-xs text-slate-500">
                                {{ optional($request->created_at)->format('Y-m-d H:i') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.reports.services.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>
@endsection
