@extends('layouts.admin')

@section('title', __('admin.service_requests.show_title'))
@section('page-title', __('admin.service_requests.show_heading'))
@section('page-subtitle', __('admin.service_requests.show_subheading'))

@section('content')
    <div class="grid gap-6">
        {{-- Request Info Card --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900">{{ $serviceRequest->request_reference }}</h3>
                    <p class="text-sm text-slate-500 mt-1">{{ __('admin.service_requests.show_subheading') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route('admin.service-requests.edit', $serviceRequest) }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700">
                        <i class="fas fa-edit"></i>
                        {{ __('admin.service_requests.actions.edit') }}
                    </a>
                    <a href="{{ route('admin.service-requests.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('admin.service_requests.actions.back') }}
                    </a>
                </div>
            </div>

            <div class="grid gap-6 md:grid-cols-2">
                {{-- User Information --}}
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-slate-900">{{ __('admin.service_requests.user_info') }}</h4>
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.table.user') }}</label>
                        <div class="mt-1 flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-600">
                                <i class="fas fa-user"></i>
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $serviceRequest->user->name }}</p>
                                <p class="text-sm text-slate-500">{{ $serviceRequest->user->email }}</p>
                                @if($serviceRequest->user->phone)
                                    <p class="text-sm text-slate-500">{{ $serviceRequest->user->phone }}</p>
                                @endif
                                <p class="text-xs text-slate-400 mt-1">{{ __('admin.users.join_date') }}: {{ $serviceRequest->user->created_at->format('Y-m-d') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Service Information --}}
                <div class="space-y-4">
                    <h4 class="text-lg font-semibold text-slate-900">{{ __('admin.service_requests.service_info') }}</h4>
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.table.service_type') }}</label>
                        <div class="mt-1">
                            @if($serviceRequest->service_type === 'bus' || $serviceRequest->service_type === 'bus_trip')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    <i class="fas fa-bus"></i>
                                    {{ __('admin.service_requests.badges.bus') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-purple-50 px-3 py-1 text-xs font-semibold text-purple-600">
                                    <i class="fas fa-car"></i>
                                    {{ __('admin.service_requests.badges.private_car') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($serviceRequest->service_type === 'bus' || $serviceRequest->service_type === 'bus_trip')
                        @if($serviceRequest->trip)
                            <div>
                                <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.trip_details') }}</label>
                                <p class="mt-1 text-slate-700">
                                    <strong>{{ __('admin.trips.table.departure') }}:</strong> {{ $serviceRequest->departure_location }}<br>
                                    <strong>{{ __('admin.trips.table.arrival') }}:</strong> {{ $serviceRequest->arrival_location }}<br>
                                    <strong>{{ __('admin.trips.table.date') }}:</strong> {{ $serviceRequest->trip_date ? $serviceRequest->trip_date->format('Y-m-d') : '-' }}<br>
                                    <strong>{{ __('admin.service_requests.passengers') }}:</strong> {{ $serviceRequest->passengers_count }}
                                </p>
                            </div>
                        @endif
                    @else
                        @if($serviceRequest->privateCar)
                            <div>
                                <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.car_details') }}</label>
                                <p class="mt-1 text-slate-700">
                                    <strong>{{ __('admin.private_cars.table.name') }}:</strong> {{ app()->getLocale() === 'ar' ? $serviceRequest->privateCar->name_ar : $serviceRequest->privateCar->name_en }}<br>
                                    <strong>{{ __('admin.service_requests.duration') }}:</strong> {{ $serviceRequest->duration_hours }} {{ __('admin.service_requests.hours') }}<br>
                                    <strong>{{ __('admin.service_requests.start_date') }}:</strong> {{ $serviceRequest->start_date ? $serviceRequest->start_date->format('Y-m-d') : '-' }}
                                </p>
                            </div>
                        @endif
                    @endif
                </div>
            </div>

            {{-- Request Details --}}
            <div class="mt-6 pt-6 border-t border-slate-200">
                <h4 class="text-lg font-semibold text-slate-900 mb-4">{{ __('admin.service_requests.request_info') }}</h4>
                <div class="grid gap-4 md:grid-cols-3">
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.table.status') }}</label>
                        <div class="mt-1">
                            @if($serviceRequest->status === 'pending')
                                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-600">
                                    {{ __('admin.service_requests.badges.pending') }}
                                </span>
                            @elseif($serviceRequest->status === 'confirmed')
                                <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                    {{ __('admin.service_requests.badges.confirmed') }}
                                </span>
                            @elseif($serviceRequest->status === 'in_progress')
                                <span class="inline-flex items-center gap-1 rounded-full bg-blue-50 px-3 py-1 text-xs font-semibold text-blue-600">
                                    {{ __('admin.service_requests.badges.in_progress') }}
                                </span>
                            @elseif($serviceRequest->status === 'completed')
                                <span class="inline-flex items-center gap-1 rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-600">
                                    {{ __('admin.service_requests.badges.completed') }}
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1 rounded-full bg-rose-50 px-3 py-1 text-xs font-semibold text-rose-600">
                                    {{ __('admin.service_requests.badges.cancelled') }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.table.price') }}</label>
                        <p class="mt-1 text-lg font-bold text-slate-900">{{ number_format($serviceRequest->total_price, 2) }} {{ __('admin.service_requests.currency') }}</p>
                    </div>
                    <div>
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.table.date') }}</label>
                        <p class="mt-1 text-slate-700">{{ $serviceRequest->created_at->format('Y-m-d H:i') }}</p>
                    </div>
                </div>

                @if($serviceRequest->notes)
                    <div class="mt-4">
                        <label class="text-xs font-medium uppercase text-slate-500">{{ __('admin.service_requests.notes') }}</label>
                        <p class="mt-1 text-slate-700">{{ $serviceRequest->notes }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

