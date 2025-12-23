@extends('layouts.hotel')

@section('title', __('hotel.hotels.page_title'))
@section('page-title', __('hotel.hotels.heading'))
@section('page-subtitle', __('hotel.hotels.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('hotel.hotels.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('hotel.hotels.subheading') }}</p>
            </div>
            <a href="{{ route('hotel.hotels.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('hotel.hotels.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('hotel.hotels.table.name') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotels.table.address') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotels.table.province') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotels.table.rooms') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotels.table.website') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.hotels.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.hotels.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($hotels as $hotel)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ \Illuminate\Support\Str::limit(app()->getLocale() === 'ar' ? $hotel->address_ar : $hotel->address_en, 50) }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ app()->getLocale() === 'ar' ? ($hotel->province->name_ar ?? '-') : ($hotel->province->name_en ?? '-') }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $hotel->rooms_count }}</td>
                            <td class="px-4 py-4">
                                @if($hotel->website_url)
                                    <a href="{{ $hotel->website_url }}" target="_blank" rel="noopener" class="text-sky-600 hover:text-sky-700 hover:underline">
                                        {{ \Illuminate\Support\Str::limit($hotel->website_url, 30) }}
                                    </a>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($hotel->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('hotel.hotels.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('hotel.hotels.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('hotel.hotel-rooms.index', ['hotel_id' => $hotel->id]) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-500/20">
                                        <i class="fas fa-bed"></i>
                                        {{ __('hotel.hotels.actions.manage_rooms') }}
                                    </a>
                                    <a href="{{ route('hotel.hotels.edit', $hotel) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('hotel.hotels.actions.edit') }}
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('hotel.hotels.empty') }}
                                <a href="{{ route('hotel.hotels.create') }}"
                                   class="mt-2 inline-block text-indigo-600 hover:text-indigo-700">
                                    {{ __('hotel.hotels.create_first') }}
                                </a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection

