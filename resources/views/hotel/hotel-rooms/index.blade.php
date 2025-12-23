@extends('layouts.hotel')

@section('title', __('hotel.hotel_rooms.page_title'))
@section('page-title', __('hotel.hotel_rooms.heading'))
@section('page-subtitle', __('hotel.hotel_rooms.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('hotel.hotel_rooms.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('hotel.hotel_rooms.subheading') }}</p>
            </div>
            <div class="flex items-center gap-3">
                @if(request()->has('hotel_id'))
                    <a href="{{ route('hotel.hotels.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('hotel.hotels.heading') }}
                    </a>
                @endif
                <a href="{{ route('hotel.hotel-rooms.create', request()->has('hotel_id') ? ['hotel_id' => request()->hotel_id] : []) }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                    <i class="fas fa-plus"></i>
                    {{ __('hotel.hotel_rooms.actions.create') }}
                </a>
            </div>
        </div>

        @if($hotels->count() > 0)
            <div class="mt-4">
                <form method="GET" action="{{ route('hotel.hotel-rooms.index') }}" class="flex items-center gap-3">
                    <label for="hotel_id" class="text-sm font-medium text-slate-600">فلترة حسب الفندق:</label>
                    <select id="hotel_id" name="hotel_id" onchange="this.form.submit()"
                            class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                        <option value="">جميع الفنادق</option>
                        @foreach($hotels as $hotel)
                            <option value="{{ $hotel->id }}" @selected(request('hotel_id') == $hotel->id)>
                                {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        @endif

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('hotel.hotel_rooms.table.hotel') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotel_rooms.table.price') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotel_rooms.table.beds') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotel_rooms.table.bathrooms') }}</th>
                        <th class="px-4 py-3">{{ __('hotel.hotel_rooms.table.rooms') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.hotel_rooms.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('hotel.hotel_rooms.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($rooms as $room)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ app()->getLocale() === 'ar' ? $room->hotel->name_ar : $room->hotel->name_en }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ number_format($room->price_per_night, 2) }} {{ __('currency', [], 'ar') ?? 'ر.س' }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $room->beds_count }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $room->bathrooms_count }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $room->rooms_count }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($room->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('hotel.hotel_rooms.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('hotel.hotel_rooms.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('hotel.hotel-rooms.edit', $room) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('hotel.hotel_rooms.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('hotel.hotel-rooms.destroy', $room) }}"
                                          onsubmit="return confirm('{{ __('hotel.hotel_rooms.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('hotel.hotel_rooms.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('hotel.hotel_rooms.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $rooms->links() }}
        </div>
    </div>
@endsection

