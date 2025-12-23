@extends('layouts.hotel')

@section('title', __('hotel.dashboard.page_title'))
@section('page-title', __('hotel.dashboard.heading'))
@section('page-subtitle', __('hotel.dashboard.subheading'))

@section('content')
    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 lg:gap-6 mb-6 lg:mb-8">
        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-md">
            <div class="absolute inset-0 pointer-events-none opacity-40">
                <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-indigo-50"></div>
                <div class="absolute -right-4 -bottom-8 h-28 w-28 rounded-full bg-indigo-100"></div>
            </div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-500 mb-2">{{ __('hotel.dashboard.stats.total_hotels') }}</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $hotels->count() }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-indigo-50 text-indigo-600">
                    <i class="fas fa-hotel text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-md">
            <div class="absolute inset-0 pointer-events-none opacity-40">
                <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-emerald-50"></div>
                <div class="absolute -right-4 -bottom-8 h-28 w-28 rounded-full bg-emerald-100"></div>
            </div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-500 mb-2">{{ __('hotel.dashboard.stats.total_rooms') }}</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $totalRooms }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-emerald-50 text-emerald-600">
                    <i class="fas fa-bed text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-md">
            <div class="absolute inset-0 pointer-events-none opacity-40">
                <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-sky-50"></div>
                <div class="absolute -right-4 -bottom-8 h-28 w-28 rounded-full bg-sky-100"></div>
            </div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-500 mb-2">{{ __('hotel.dashboard.stats.open_conversations') }}</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $totalConversations }}</p>
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-sky-50 text-sky-600">
                    <i class="fas fa-comments text-2xl"></i>
                </div>
            </div>
        </div>

        <div class="group relative overflow-hidden rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200/80 transition-all duration-300 hover:shadow-md">
            <div class="absolute inset-0 pointer-events-none opacity-40">
                <div class="absolute -left-6 -top-6 h-24 w-24 rounded-full bg-rose-50"></div>
                <div class="absolute -right-4 -bottom-8 h-28 w-28 rounded-full bg-rose-100"></div>
            </div>
            <div class="relative flex items-center justify-between">
                <div class="flex-1">
                    <p class="text-sm font-medium text-slate-500 mb-2">{{ __('hotel.dashboard.stats.unread_messages') }}</p>
                    <p class="text-3xl font-extrabold text-slate-900">{{ $unreadMessages }}</p>
                    @if($unreadMessages > 0)
                        <span class="inline-flex items-center gap-1 mt-1 text-xs font-semibold text-rose-600">
                            <span class="h-1.5 w-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                            {{ __('hotel.dashboard.new_messages') }}
                        </span>
                    @endif
                </div>
                <div class="flex h-14 w-14 items-center justify-center rounded-xl bg-rose-50 text-rose-600">
                    <i class="fas fa-envelope text-2xl"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
        <!-- My Hotels Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ __('hotel.dashboard.my_hotels') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('hotel.dashboard.manage_hotels_subtitle') }}</p>
                </div>
                <a href="{{ route('hotel.hotels.create') }}"
                   class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-4 py-2 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30">
                    <i class="fas fa-plus"></i>
                    <span class="hidden sm:inline">{{ __('hotel.dashboard.add_hotel') }}</span>
                    <span class="sm:hidden"><i class="fas fa-plus"></i></span>
                </a>
            </div>

            @if($hotels->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($hotels as $hotel)
                        <a href="{{ route('hotel.hotels.edit', $hotel) }}"
                           class="group block rounded-xl border border-slate-200 p-4 transition-all duration-200 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md">
                            <div class="flex items-center justify-between gap-4">
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center gap-3 mb-2">
                                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-600 group-hover:bg-indigo-500/20 transition">
                                            <i class="fas fa-hotel"></i>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h3 class="font-bold text-slate-900 truncate group-hover:text-indigo-700 transition">
                                                {{ app()->getLocale() === 'ar' ? $hotel->name_ar : $hotel->name_en }}
                                            </h3>
                                            <p class="text-xs text-slate-500 mt-0.5">
                                                {{ app()->getLocale() === 'ar' ? ($hotel->province->name_ar ?? '-') : ($hotel->province->name_en ?? '-') }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-4 mt-3 text-xs text-slate-600">
                                        <span class="flex items-center gap-1">
                                            <i class="fas fa-bed text-slate-400"></i>
                                            <span>{{ $hotel->rooms_count }} {{ __('hotel.dashboard.rooms') }}</span>
                                        </span>
                                        @if($hotel->website_url)
                                            <a href="{{ $hotel->website_url }}" target="_blank" rel="noopener"
                                               class="flex items-center gap-1 text-indigo-600 hover:text-indigo-700"
                                               onclick="event.stopPropagation()">
                                                <i class="fas fa-link"></i>
                                                <span>الموقع</span>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                                <div class="flex flex-col items-end gap-2 shrink-0">
                                    @if($hotel->is_active)
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-700 border border-emerald-200">
                                            <span class="h-2 w-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                            {{ __('hotel.dashboard.active') }}
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-600 border border-slate-200">
                                            <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                            {{ __('hotel.dashboard.inactive') }}
                                        </span>
                                    @endif
                                    <i class="fas fa-chevron-left text-slate-400 group-hover:text-indigo-600 transition"></i>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <a href="{{ route('hotel.hotels.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                        {{ __('hotel.dashboard.view_all_hotels') }}
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                        <i class="fas fa-hotel text-2xl"></i>
                    </div>
                    <p class="text-slate-600 font-medium mb-2">{{ __('hotel.dashboard.no_hotels') }}</p>
                    <p class="text-sm text-slate-500 mb-6">{{ __('hotel.dashboard.create_hotel_description') }}</p>
                    <a href="{{ route('hotel.hotels.create') }}"
                       class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-500/25 transition hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-500/30">
                        <i class="fas fa-plus"></i>
                        {{ __('hotel.dashboard.create_first_hotel') }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Conversations Card -->
        <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ __('hotel.dashboard.recent_conversations') }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ __('hotel.dashboard.conversations_subtitle') }}</p>
                </div>
                <a href="{{ route('hotel.conversations.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-3 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-50 hover:border-slate-300">
                    <span class="hidden sm:inline">{{ __('hotel.dashboard.view_all') }}</span>
                    <i class="fas fa-arrow-left"></i>
                </a>
            </div>

            @php
                $recentConversations = \App\Models\Conversation::whereIn('hotel_id', $hotels->pluck('id'))
                    ->where('status', 'open')
                    ->with('user', 'lastMessage', 'hotel')
                    ->orderByDesc('last_message_at')
                    ->limit(5)
                    ->get();

                $unreadConversations = \App\Models\Conversation::whereIn('hotel_id', $hotels->pluck('id'))
                    ->whereHas('messages', function($query) {
                        $query->where('sender_type', 'user')->where('is_read', false);
                    })
                    ->pluck('id')
                    ->toArray();
            @endphp

            @if($recentConversations->count() > 0)
                <div class="space-y-3 max-h-96 overflow-y-auto">
                    @foreach($recentConversations as $conversation)
                        @php
                            $hasUnread = in_array($conversation->id, $unreadConversations);
                        @endphp
                        <a href="{{ route('hotel.conversations.show', $conversation) }}"
                           @class([
                               'group block rounded-xl border p-4 transition-all duration-200',
                               'border-rose-200 bg-rose-50/50 hover:bg-rose-50 hover:shadow-md' => $hasUnread,
                               'border-slate-200 hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md' => !$hasUnread,
                           ])>
                            <div class="flex items-start gap-3">
                                <div @class([
                                    'flex h-12 w-12 items-center justify-center rounded-full shrink-0',
                                    'bg-rose-500/10 text-rose-600' => $hasUnread,
                                    'bg-indigo-500/10 text-indigo-600' => !$hasUnread,
                                ])>
                                    <i class="fas fa-user"></i>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-bold text-slate-900 truncate group-hover:text-indigo-700 transition">
                                            {{ $conversation->user->name }}
                                        </h4>
                                        @if($hasUnread)
                                            <span class="inline-flex items-center justify-center h-5 w-5 rounded-full bg-rose-500 text-white text-xs font-bold shrink-0">
                                                !
                                            </span>
                                        @endif
                                    </div>
                                    @if($conversation->hotel)
                                        <p class="text-xs text-slate-500 mb-2 truncate">
                                            <i class="fas fa-hotel ml-1"></i>
                                            {{ app()->getLocale() === 'ar' ? $conversation->hotel->name_ar : $conversation->hotel->name_en }}
                                        </p>
                                    @endif
                                    @if($conversation->lastMessage)
                                        <p class="text-sm text-slate-600 truncate mb-2">
                                            {{ \Illuminate\Support\Str::limit($conversation->lastMessage->message, 50) }}
                                        </p>
                                    @endif
                                    @if($conversation->last_message_at)
                                        <div class="flex items-center gap-2 text-xs text-slate-400">
                                            <i class="fas fa-clock"></i>
                                            <span>{{ $conversation->last_message_at->diffForHumans() }}</span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="mt-4 pt-4 border-t border-slate-200">
                    <a href="{{ route('hotel.conversations.index') }}"
                       class="inline-flex items-center gap-2 text-sm font-semibold text-indigo-600 hover:text-indigo-700 transition">
                        {{ __('hotel.dashboard.view_all_conversations') }}
                        <i class="fas fa-arrow-left"></i>
                    </a>
                </div>
            @else
                <div class="text-center py-12">
                    <div class="inline-flex h-16 w-16 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-4">
                        <i class="fas fa-comments text-2xl"></i>
                    </div>
                    <p class="text-slate-600 font-medium mb-2">{{ __('hotel.dashboard.no_conversations') }}</p>
                    <p class="text-sm text-slate-500">{{ __('hotel.dashboard.no_conversations_description') }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 lg:mt-8 rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
        <h2 class="text-lg font-bold text-slate-900 mb-4">{{ __('hotel.dashboard.quick_actions') }}</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <a href="{{ route('hotel.hotels.create') }}"
               class="group flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition-all hover:border-indigo-300 hover:bg-indigo-50/50 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-indigo-500/10 text-indigo-600 group-hover:bg-indigo-500/20 transition">
                    <i class="fas fa-plus text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-indigo-700 transition">{{ __('hotel.dashboard.add_hotel') }}</p>
                    <p class="text-xs text-slate-500">{{ __('hotel.dashboard.add_hotel_hint') }}</p>
                </div>
            </a>

            <a href="{{ route('hotel.hotel-rooms.create') }}"
               class="group flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition-all hover:border-emerald-300 hover:bg-emerald-50/50 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-emerald-500/10 text-emerald-600 group-hover:bg-emerald-500/20 transition">
                    <i class="fas fa-bed text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-emerald-700 transition">{{ __('hotel.dashboard.add_room') }}</p>
                    <p class="text-xs text-slate-500">{{ __('hotel.dashboard.add_room_hint') }}</p>
                </div>
            </a>

            <a href="{{ route('hotel.conversations.index') }}"
               class="group flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition-all hover:border-blue-300 hover:bg-blue-50/50 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-blue-500/10 text-blue-600 group-hover:bg-blue-500/20 transition">
                    <i class="fas fa-comments text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-blue-700 transition">{{ __('hotel.dashboard.view_conversations') }}</p>
                    <p class="text-xs text-slate-500">{{ __('hotel.dashboard.view_conversations_hint') }}</p>
                </div>
            </a>

            <a href="{{ route('hotel.hotels.index') }}"
               class="group flex items-center gap-3 rounded-xl border border-slate-200 p-4 transition-all hover:border-purple-300 hover:bg-purple-50/50 hover:shadow-md">
                <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-500/10 text-purple-600 group-hover:bg-purple-500/20 transition">
                    <i class="fas fa-hotel text-xl"></i>
                </div>
                <div>
                    <p class="font-semibold text-slate-900 group-hover:text-purple-700 transition">{{ __('hotel.dashboard.manage_hotels') }}</p>
                    <p class="text-xs text-slate-500">{{ __('hotel.dashboard.manage_hotels_hint') }}</p>
                </div>
            </a>
        </div>
    </div>
@endsection
