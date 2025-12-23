@extends('layouts.admin')

@section('title', __('admin.events.show_title'))
@section('page-title', __('admin.events.show_heading'))
@section('page-subtitle', __('admin.events.show_subheading'))

@section('content')
    <div class="grid gap-6">
        {{-- Event Info Card --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ app()->getLocale() === 'ar' ? $event->name_ar : $event->name_en }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('admin.events.show_subheading') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.events.edit', $event) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        <i class="fas fa-edit"></i>
                        {{ __('admin.events.actions.edit') }}
                    </a>
                    <a href="{{ route('admin.events.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('admin.events.actions.back') }}
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.location') }}</label>
                        <p class="mt-1 font-semibold text-slate-900">{{ app()->getLocale() === 'ar' ? $event->location_ar : $event->location_en }}</p>
                        @if($event->location_url)
                            <a href="{{ $event->location_url }}" target="_blank" rel="noopener" class="text-sm text-indigo-600 hover:underline mt-1 inline-block">
                                <i class="fas fa-map-marker-alt"></i> {{ __('admin.events.view_location') }}
                            </a>
                        @endif
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.date') }}</label>
                        <p class="mt-1 text-slate-700">{{ $event->event_date->format('Y-m-d H:i') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.price') }}</label>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ number_format($event->price, 2) }} {{ __('admin.events.currency') }}</p>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.available') }}</label>
                        <p class="mt-1 text-slate-700">{{ $event->available_tickets }} {{ __('admin.events.tickets') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.sold') }}</label>
                        <p class="mt-1 text-slate-700">{{ $event->tickets_count ?? 0 }} {{ __('admin.events.tickets') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.remaining') }}</label>
                        <p class="mt-1 text-lg font-bold text-emerald-600">{{ $event->remaining_tickets }} {{ __('admin.events.tickets') }}</p>
                    </div>

                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.table.status') }}</label>
                        <div class="mt-1">
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
                        </div>
                    </div>
                </div>
            </div>

            @if($event->description_ar || $event->description_en)
                <div class="mt-6 pt-6 border-t border-slate-200">
                    <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.events.form.description_ar') }}</label>
                    <p class="mt-1 text-slate-700 whitespace-pre-line">{{ app()->getLocale() === 'ar' ? $event->description_ar : $event->description_en }}</p>
                </div>
            @endif
        </div>

        {{-- Tickets List --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <h4 class="text-lg font-semibold text-slate-900 mb-4">{{ __('admin.events.tickets_heading') }}</h4>
            
            @if($event->tickets->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                        <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                            <tr>
                                <th class="px-4 py-3">{{ __('admin.events.ticket_reference') }}</th>
                                <th class="px-4 py-3">{{ __('admin.service_requests.table.user') }}</th>
                                <th class="px-4 py-3">{{ __('admin.events.tickets_count') }}</th>
                                <th class="px-4 py-3">{{ __('admin.events.total_price') }}</th>
                                <th class="px-4 py-3">{{ __('admin.service_requests.table.status') }}</th>
                                <th class="px-4 py-3">{{ __('admin.service_requests.table.date') }}</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($event->tickets as $ticket)
                                <tr>
                                    <td class="px-4 py-4 font-mono text-xs text-slate-600">{{ $ticket->ticket_reference }}</td>
                                    <td class="px-4 py-4 text-slate-800">{{ $ticket->user->name }}</td>
                                    <td class="px-4 py-4 text-slate-600">{{ $ticket->tickets_count }}</td>
                                    <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($ticket->total_price, 2) }} {{ __('admin.events.currency') }}</td>
                                    <td class="px-4 py-4">
                                        @if($ticket->status === 'pending')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-600">
                                                {{ __('admin.service_requests.badges.pending') }}
                                            </span>
                                        @elseif($ticket->status === 'confirmed')
                                            <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                                {{ __('admin.service_requests.badges.confirmed') }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                                {{ __('admin.service_requests.badges.cancelled') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-4 text-slate-600 text-xs">{{ $ticket->created_at->format('Y-m-d H:i') }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-sm text-slate-500 text-center py-6">{{ __('admin.events.no_tickets') }}</p>
            @endif
        </div>
    </div>
@endsection

