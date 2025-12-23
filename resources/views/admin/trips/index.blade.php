@extends('layouts.admin')

@section('title', __('admin.trips.page_title'))
@section('page-title', __('admin.trips.heading'))
@section('page-subtitle', __('admin.trips.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.trips.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.trips.subheading') }}</p>
            </div>
            <a href="{{ route('admin.trips.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.trips.actions.create') }}
            </a>
        </div>

        {{-- Filters --}}
        <form method="GET" action="{{ route('admin.trips.index') }}" class="mt-6 space-y-4">
            <div class="grid gap-4 sm:grid-cols-3">
                <input type="date" name="date" value="{{ request('date') }}"
                       class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                
                <select name="bus_id" class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                    <option value="">{{ __('admin.buses.heading') }}</option>
                    @foreach($buses as $bus)
                        <option value="{{ $bus->id }}" {{ request('bus_id') == $bus->id ? 'selected' : '' }}>
                            {{ app()->getLocale() === 'ar' ? $bus->name_ar : $bus->name_en }}
                        </option>
                    @endforeach
                </select>

                <button type="submit"
                        class="inline-flex items-center justify-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('admin.trips.search') }}
                </button>
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.trips.table.departure') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.arrival') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.bus') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.date') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.time') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.duration') }}</th>
                        <th class="px-4 py-3">{{ __('admin.trips.table.price') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.trips.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.trips.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($trips as $trip)
                        <tr>
                            <td class="px-4 py-4 text-slate-800">
                                {{ app()->getLocale() === 'ar' ? $trip->departure_location_ar : $trip->departure_location_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-800">
                                {{ app()->getLocale() === 'ar' ? $trip->arrival_location_ar : $trip->arrival_location_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ app()->getLocale() === 'ar' ? $trip->bus->name_ar : $trip->bus->name_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">{{ $trip->trip_date->format('Y-m-d') }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $trip->trip_time }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $trip->duration_minutes }} {{ __('admin.trips.duration_unit') }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($trip->price, 2) }} {{ __('admin.trips.currency') }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($trip->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.hotels.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.hotels.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.trips.edit', $trip) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.trips.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.trips.destroy', $trip) }}"
                                          onsubmit="return confirm('{{ __('admin.trips.messages.deleted') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.trips.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.trips.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $trips->links() }}
        </div>
    </div>
@endsection

