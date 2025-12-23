@extends('layouts.admin')

@section('title', __('admin.users.page_title'))
@section('page-title', __('admin.users.heading'))
@section('page-subtitle', __('admin.users.subheading'))

@section('content')
    <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-900">{{ __('admin.users.heading') }}</h2>
                <p class="text-sm text-slate-500">{{ __('admin.users.subheading') }}</p>
            </div>
        </div>

        {{-- Search and Filter --}}
        <div class="mt-6 grid gap-4 sm:grid-cols-2">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex items-center gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="{{ __('admin.users.search_placeholder') }}"
                       class="flex-1 rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                <button type="submit"
                        class="inline-flex items-center gap-2 rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-700">
                    <i class="fas fa-search"></i>
                    {{ __('admin.users.search') }}
                </button>
                @if(request('search'))
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-times"></i>
                    </a>
                @endif
            </form>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.users.index', ['status' => 'active']) }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition',
                       'bg-emerald-500 text-white' => request('status') === 'active',
                       'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100' => request('status') !== 'active',
                   ])>
                    <i class="fas fa-check-circle"></i>
                    {{ __('admin.users.active') }}
                </a>
                <a href="{{ route('admin.users.index', ['status' => 'inactive']) }}"
                   @class([
                       'inline-flex items-center gap-2 rounded-xl px-4 py-2 text-sm font-semibold transition',
                       'bg-rose-500 text-white' => request('status') === 'inactive',
                       'border border-slate-200 bg-white text-slate-600 hover:bg-slate-100' => request('status') !== 'inactive',
                   ])>
                    <i class="fas fa-ban"></i>
                    {{ __('admin.users.inactive') }}
                </a>
                @if(request('status'))
                    <a href="{{ route('admin.users.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        {{ __('admin.users.all') }}
                    </a>
                @endif
            </div>
        </div>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-right text-sm">
                <thead class="bg-slate-50 text-xs font-medium uppercase tracking-wider text-slate-500">
                    <tr>
                        <th class="px-4 py-3">{{ __('admin.users.table.name') }}</th>
                        <th class="px-4 py-3">{{ __('admin.users.table.email') }}</th>
                        <th class="px-4 py-3">{{ __('admin.users.table.phone') }}</th>
                        <th class="px-4 py-3">{{ __('admin.users.table.registered_at') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.users.table.status') }}</th>
                        <th class="px-4 py-3 text-center">{{ __('admin.users.table.actions') }}</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $user->email }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $user->phone ?? '-' }}</td>
                            <td class="px-4 py-4 text-slate-600">{{ $user->created_at->format('Y-m-d') }}</td>
                            <td class="px-4 py-4 text-center">
                                @if ($user->is_active)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-3 py-1 text-xs font-semibold text-emerald-600">
                                        <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                        {{ __('admin.users.badges.active') }}
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500">
                                        <span class="h-2 w-2 rounded-full bg-slate-400"></span>
                                        {{ __('admin.users.badges.inactive') }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.users.manage-hotels', $user) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-purple-500/10 px-3 py-1.5 text-xs font-semibold text-purple-600 hover:bg-purple-500/20"
                                       title="{{ __('admin.users.actions.manage_hotels') }}">
                                        <i class="fas fa-hotel"></i>
                                        {{ __('admin.users.actions.manage_hotels') }}
                                    </a>
                                    @if($user->managedHotels->count() > 0)
                                        <span class="inline-flex items-center justify-center rounded-full bg-purple-500 text-xs font-semibold text-white h-5 w-5"
                                              title="{{ $user->managedHotels->count() }} {{ __('admin.users.hotel_count') }}">
                                            {{ $user->managedHotels->count() }}
                                        </span>
                                    @endif
                                    <a href="{{ route('admin.conversations.get-or-create', $user) }}"
                                       class="inline-flex items-center gap-1 rounded-lg bg-blue-500/10 px-3 py-1.5 text-xs font-semibold text-blue-600 hover:bg-blue-500/20"
                                       title="{{ __('admin.users.actions.start_chat') }}">
                                        <i class="fas fa-comments"></i>
                                        {{ __('admin.users.actions.start_chat') }}
                                    </a>
                                    <form method="POST" action="{{ route('admin.users.toggle', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                @class([
                                                    'inline-flex items-center gap-1 rounded-lg px-3 py-1.5 text-xs font-semibold transition',
                                                    'bg-amber-500/10 text-amber-600 hover:bg-amber-500/20' => $user->is_active,
                                                    'bg-emerald-500/10 text-emerald-600 hover:bg-emerald-500/20' => !$user->is_active,
                                                ])
                                                title="{{ $user->is_active ? __('admin.users.actions.deactivate') : __('admin.users.actions.activate') }}">
                                            <i class="fas {{ $user->is_active ? 'fa-ban' : 'fa-check-circle' }}"></i>
                                            {{ $user->is_active ? __('admin.users.actions.deactivate') : __('admin.users.actions.activate') }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                                          onsubmit="return confirm('{{ __('admin.users.confirm_delete') }}')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="inline-flex items-center gap-1 rounded-lg bg-rose-500/10 px-3 py-1.5 text-xs font-semibold text-rose-600 hover:bg-rose-500/20">
                                            <i class="fas fa-trash"></i>
                                            {{ __('admin.users.actions.delete') }}
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-sm text-slate-500">
                                {{ __('admin.users.empty') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->appends(request()->query())->links() }}
        </div>
    </div>
@endsection

