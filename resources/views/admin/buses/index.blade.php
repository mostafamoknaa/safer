@extends('layouts.admin')

@section('title', __('admin.buses.page_title'))
@section('page-title', __('admin.buses.heading'))
@section('page-subtitle', __('admin.buses.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.buses.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.buses.subheading') }}</p>
            </div>
            <a href="{{ route('admin.buses.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.buses.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.buses.table.name') }}</th>
                        <th class="px-4 py-3">{{ __('admin.buses.table.total_seats') }}</th>
                        <th class="px-4 py-3">{{ __('admin.buses.table.trips_count') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.buses.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.buses.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($buses as $bus)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">
                                {{ app()->getLocale() === 'ar' ? $bus->name_ar : $bus->name_en }}
                            </td>
                            <td class="px-4 py-4 text-slate-600">{{ $bus->total_seats }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $bus->trips_count }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($bus->is_active)
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
                                    <a href="{{ route('admin.buses.edit', $bus) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.buses.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.buses.destroy', $bus) }}"
                                          onsubmit="return confirm('{{ __('admin.buses.messages.deleted') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.buses.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.buses.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $buses->links() }}
        </div>
    </div>
@endsection

