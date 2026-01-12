@extends('layouts.admin')

@section('title', __('admin.services.page_title'))
@section('page-title', __('admin.services.heading'))
@section('page-subtitle', __('admin.services.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.services.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.services.subheading') }}</p>
            </div>
            <a href="{{ route('admin.master-services.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.services.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.services.table.image') }}</th>
                        <th class="px-4 py-3">{{ __('admin.services.table.name') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.services.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.services.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($services as $service)
                        <tr>
                            <td class="px-4 py-4">
                                @if($service->image)
                                    <img src="{{ asset('storage/' . $service->image) }}" alt="{{ $service->name }}" class="h-12 w-12 rounded-lg object-cover shadow-sm">
                                @else
                                    <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-slate-100 text-slate-400">
                                        <i class="fas fa-image text-xl"></i>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $service->name }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($service->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.services.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.services.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.master-services.edit', $service) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.services.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.master-services.destroy', $service) }}"
                                          onsubmit="return confirm('{{ __('admin.services.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.services.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.services.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
@endsection
