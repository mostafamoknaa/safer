@extends('layouts.admin')

@section('title', __('admin.events.page_title'))
@section('page-title', __('admin.events.heading'))
@section('page-subtitle', __('admin.events.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.events.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.events.subheading') }}</p>
            </div>
            <a href="{{ route('admin.events.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.events.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.events.table.name') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.location') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.date') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.price') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.available') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.sold') }}</th>
                        <th class="px-4 py-3">{{ __('admin.events.table.remaining') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.events.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.events.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($events as $event)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">
                                {{ app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                {{ app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">{{ $event->event_date->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($event->price, 2) }} {{ __('admin.events.currency') }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $event->available_tickets }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $event->tickets_count ?? 0 }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $event->remaining_tickets }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($event->is_active && $event->event_date >= now())
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
                                    <a href="{{ route('admin.events.show', $event) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-500/20">
                                        <i class="fas fa-eye"></i>
                                        {{ __('admin.events.actions.view') }}
                                    </a>
                                    <a href="{{ route('admin.events.edit', $event) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.events.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.events.destroy', $event) }}"
                                          onsubmit="return confirm('{{ __('admin.events.messages.deleted') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.events.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.events.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $events->links() }}
        </div>
    </div>
@endsection

