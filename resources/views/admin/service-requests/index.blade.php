@extends('layouts.admin')

@section('title', __('admin.service_requests.page_title'))
@section('page-title', __('admin.service_requests.heading'))
@section('page-subtitle', __('admin.service_requests.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.service_requests.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.service_requests.subheading') }}</p>
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.reference') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.user') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.service_type') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.details') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.price') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.status') }}</th>
                        <th class="px-4 py-3">{{ __('admin.service_requests.table.date') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.service_requests.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($requests as $request)
                        <tr>
                            <td class="px-4 py-4 font-mono text-xs text-slate-600">{{ $request->request_reference }}</td>
                            <td class="px-4 py-4 text-slate-800">{{ $request->user->name }}</td>
                            <td class="px-4 py-4">
                                @if($request->service_type === 'bus')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ __('admin.service_requests.badges.bus') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-600">
                                        {{ __('admin.service_requests.badges.private_car') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-600 text-xs">
                                @if($request->service_type === 'bus')
                                    {{ $request->departure_location }} → {{ $request->arrival_location }}
                                    <br>
                                    <span class="text-slate-400">{{ $request->passengers_count }} {{ __('admin.service_requests.passengers') }}</span>
                                @else
                                    {{ app()->getLocale() === 'ar' ? $request->privateCar->name_ar : $request->privateCar->name_en }}
                                    <br>
                                    <span class="text-slate-400">{{ $request->duration_hours }} {{ __('admin.service_requests.hours') }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ number_format($request->total_price, 2) }} {{ __('admin.service_requests.currency') }}</td>
                            <td class="px-4 py-4">
                                @if($request->status === 'pending')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-600">
                                        {{ __('admin.service_requests.badges.pending') }}
                                    </span>
                                @elseif($request->status === 'confirmed')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        {{ __('admin.service_requests.badges.confirmed') }}
                                    </span>
                                @elseif($request->status === 'completed')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                        {{ __('admin.service_requests.badges.completed') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                        {{ __('admin.service_requests.badges.cancelled') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-600 text-xs">{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-4 text-center">
                                <a href="{{ route('admin.service-requests.show', $request) }}"
                                   class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                    <i class="fas fa-eye"></i>
                                    {{ __('admin.service_requests.actions.view') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.service_requests.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $requests->links() }}
        </div>
    </div>
@endsection

