@extends('layouts.admin')

@section('title', __('admin.policies.page_title'))
@section('page-title', __('admin.policies.heading'))
@section('page-subtitle', __('admin.policies.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.policies.title') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.policies.description') }}</p>
            </div>
            <a href="{{ route('admin.policies.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.policies.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.policies.table.title_ar') }}</th>
                        <th class="px-4 py-3">{{ __('admin.policies.table.title_en') }}</th>
                        <th class="px-4 py-3">{{ __('admin.policies.table.slug') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.policies.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.policies.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($policies as $policy)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $policy->title_ar }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $policy->title_en }}</td>
                            <td class="px-4 py-4 text-slate-500 text-xs">{{ $policy->slug }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($policy->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.policies.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.policies.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.policies.edit', $policy) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.policies.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.policies.destroy', $policy) }}"
                                          onsubmit="return confirm('{{ __('admin.policies.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.policies.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.policies.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $policies->links() }}
        </div>
    </div>
@endsection

