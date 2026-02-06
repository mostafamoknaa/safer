@extends('layouts.admin')

@section('title', __('admin.faqs.page_title'))
@section('page-title', __('admin.faqs.heading'))
@section('page-subtitle', __('admin.faqs.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.faqs.title') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.faqs.description') }}</p>
            </div>
            <a href="{{ route('admin.faqs.create') }}"
               class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-slate-400/40 transition hover:bg-slate-700">
                <i class="fas fa-plus"></i>
                {{ __('admin.faqs.actions.create') }}
            </a>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.faqs.table.order') }}</th>
                        <th class="px-4 py-3">{{ __('admin.faqs.table.question_ar') }}</th>
                        <th class="px-4 py-3">{{ __('admin.faqs.table.question_en') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.faqs.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.faqs.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($faqs as $faq)
                        <tr>
                            <td class="px-4 py-4 text-slate-500">{{ $faq->order_column }}</td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $faq->question_ar }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $faq->question_en }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($faq->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.faqs.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.faqs.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.faqs.edit', $faq) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-pen-to-square"></i>
                                        {{ __('admin.faqs.actions.edit') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.faqs.destroy', $faq) }}"
                                          onsubmit="return confirm('{{ __('admin.faqs.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.faqs.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.faqs.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $faqs->links() }}
        </div>
    </div>
@endsection

