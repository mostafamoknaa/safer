@extends('layouts.admin')

@section('title', __('admin.contact_links.page_title'))
@section('page-title', __('admin.contact_links.heading'))
@section('page-subtitle', __('admin.contact_links.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.contact_links.title') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.contact_links.description') }}</p>
            </div>
            <a href="{{ route('admin.contact-links.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.contact_links.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.contact_links.table.type') }}</th>
                        <th class="px-4 py-3">{{ __('admin.contact_links.table.title_ar') }}</th>
                        <th class="px-4 py-3">{{ __('admin.contact_links.table.title_en') }}</th>
                        <th class="px-4 py-3">{{ __('admin.contact_links.table.url') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.contact_links.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.contact_links.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($links as $link)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $link->type }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $link->title_ar }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $link->title_en }}</td>
                            <td class="px-4 py-4">
                                <a href="{{ $link->url }}" target="_blank" rel="noopener" class="text-sky-600 hover:text-sky-700 hover:underline">
                                    {{ $link->url }}
                                </a>
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($link->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.contact_links.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.contact_links.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.contact-links.edit', $link) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-sky-500/10 px-3 py-1.5 text-xs font-semibold text-sky-600 hover:bg-sky-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.contact_links.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.contact-links.destroy', $link) }}"
                                          onsubmit="return confirm('{{ __('admin.contact_links.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.contact_links.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.contact_links.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $links->links() }}
        </div>
    </div>
@endsection

