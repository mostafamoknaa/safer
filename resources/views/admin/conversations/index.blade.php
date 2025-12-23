@extends('layouts.admin')

@section('title', __('admin.conversations.page_title'))
@section('page-title', __('admin.conversations.heading'))
@section('page-subtitle', __('admin.conversations.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.conversations.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.conversations.subheading') }}</p>
            </div>
            @if($unreadCount > 0)
                <div class="inline-flex items-center gap-2 rounded-xl bg-rose-500/10 px-4 py-2 text-sm font-semibold text-rose-600">
                    <i class="fas fa-envelope"></i>
                    <span>{{ $unreadCount }} {{ __('admin.conversations.unread_messages') }}</span>
                </div>
            @endif
        </div>

        {{-- Search and Filter --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <form method="GET" action="{{ route('admin.conversations.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('admin.conversations.search_placeholder') }}"
                       class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('admin.conversations.search') }}
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.conversations.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.conversations.index', ['status' => 'open']) }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition',
                       'bg-emerald-500 text-white' => request('status') === 'open',
                       'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100' => request('status') !== 'open',
                   ])>
                    <i class="fas fa-comments"></i>
                    {{ __('admin.conversations.open') }}
                </a>
                <a href="{{ route('admin.conversations.index', ['status' => 'closed']) }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition',
                       'bg-slate-500 text-white' => request('status') === 'closed',
                       'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100' => request('status') !== 'closed',
                   ])>
                    <i class="fas fa-archive"></i>
                    {{ __('admin.conversations.closed') }}
                </a>
                @if(request('status'))
                    <a href="{{ route('admin.conversations.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        {{ __('admin.conversations.all') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.conversations.table.user') }}</th>
                        <th class="px-4 py-3">{{ __('admin.conversations.table.last_message') }}</th>
                        <th class="px-4 py-3">{{ __('admin.conversations.table.last_message_at') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.conversations.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.conversations.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($conversations as $conversation)
                        @php
                            $unreadCount = $conversation->messages()->where('sender_type', 'user')->where('is_read', false)->count();
                        @endphp
                        <tr @class(['bg-rose-50/50' => $unreadCount > 0])>
                            <td class="px-4 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-indigo-500/10 text-indigo-600">
                                        <i class="fas fa-user"></i>
                                    </div>
                                    <div>
                                        <div class="font-semibold text-slate-800">{{ $conversation->user->name }}</div>
                                        <div class="text-xs text-slate-500">{{ $conversation->user->email }}</div>
                                    </div>
                                    @if($unreadCount > 0)
                                        <span class="inline-flex items-center justify-center rounded-full bg-rose-500 text-xs font-semibold text-white h-5 w-5">
                                            {{ $unreadCount }}
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-4 py-4 text-slate-600">
                                @if($conversation->lastMessage)
                                    <div class="flex items-center gap-2">
                                        @if($conversation->lastMessage->type === 'file')
                                            <i class="fas fa-file text-slate-400"></i>
                                        @endif
                                        <span class="truncate max-w-xs">
                                            {{ \Illuminate\Support\Str::limit($conversation->lastMessage->message, 50) }}
                                        </span>
                                    </div>
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-slate-600 text-xs">
                                @if($conversation->last_message_at)
                                    {{ $conversation->last_message_at->diffForHumans() }}
                                @else
                                    <span class="text-slate-400">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                @if ($conversation->status === 'open')
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.conversations.badges.open') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.conversations.badges.closed') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.conversations.show', $conversation) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-indigo-500/10 px-3 py-1.5 text-xs font-semibold text-indigo-600 hover:bg-indigo-500/20">
                                        <i class="fas fa-comment-dots"></i>
                                        {{ __('admin.conversations.actions.open') }}
                                    </a>
                                    @if($conversation->status === 'open')
                                        <form method="POST" action="{{ route('admin.conversations.close', $conversation) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center gap-1 rounded-lg bg-slate-500/10 px-3 py-1.5 text-xs font-semibold text-slate-600 hover:bg-slate-500/20">
                                                <i class="fas fa-archive"></i>
                                                {{ __('admin.conversations.actions.close') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.conversations.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $conversations->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

