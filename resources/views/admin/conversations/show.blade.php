@extends('layouts.admin')

@section('title', __('admin.conversations.show_title'))
@section('page-title', __('admin.conversations.show_heading'))
@section('page-subtitle', __('admin.conversations.show_subheading', ['user' => $conversation->user->name]))

@section('content')
    <div class="grid gap-6">
        {{-- User Info Card --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-full bg-indigo-500/10 text-2xl text-indigo-600">
                        <i class="fas fa-user"></i>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900">{{ $conversation->user->name }}</h3>
                        <p class="text-sm text-slate-500">{{ $conversation->user->email }}</p>
                        @if($conversation->user->phone)
                            <p class="text-sm text-slate-500">{{ $conversation->user->phone }}</p>
                        @endif
                    </div>
                </div>
                <div class="flex items-center gap-3">
                    @if($conversation->status === 'open')
                        <form method="POST" action="{{ route('admin.conversations.close', $conversation) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                                <i class="fas fa-archive"></i>
                                {{ __('admin.conversations.actions.close') }}
                            </button>
                        </form>
                    @else
                        <form method="POST" action="{{ route('admin.conversations.reopen', $conversation) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-emerald-500 px-4 py-2 text-sm font-semibold text-white transition hover:bg-emerald-600">
                                <i class="fas fa-redo"></i>
                                {{ __('admin.conversations.actions.reopen') }}
                            </button>
                        </form>
                    @endif
                    <a href="{{ route('admin.conversations.index') }}"
                       class="inline-flex items-center gap-2 rounded-xl border border-slate-200 px-4 py-2 text-sm font-semibold text-slate-600 transition hover:bg-slate-100">
                        <i class="fas fa-arrow-right"></i>
                        {{ __('admin.conversations.back_to_list') }}
                    </a>
                </div>
            </div>
        </div>

        {{-- Messages Area --}}
        <div class="rounded-3xl border border-slate-200/80 bg-white/90 p-6 shadow-lg shadow-slate-200/60 backdrop-blur">
            <div id="messagesContainer" class="space-y-4 max-h-[600px] overflow-y-auto mb-6 pb-6">
                @forelse ($conversation->messages as $message)
                    <div @class([
                        'flex gap-3',
                        'flex-row-reverse' => $message->sender_type === 'admin',
                    ])>
                        <div class="flex-shrink-0">
                            <div @class([
                                'flex h-10 w-10 items-center justify-center rounded-full text-sm font-semibold',
                                'bg-indigo-500 text-white' => $message->sender_type === 'admin',
                                'bg-slate-300 text-slate-700' => $message->sender_type === 'user',
                            ])>
                                <i class="fas {{ $message->sender_type === 'admin' ? 'fa-user-tie' : 'fa-user' }}"></i>
                            </div>
                        </div>
                        <div @class([
                            'flex-1 rounded-2xl px-4 py-3 max-w-2xl',
                            'bg-indigo-500 text-white' => $message->sender_type === 'admin',
                            'bg-slate-100 text-slate-800' => $message->sender_type === 'user',
                        ])>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-semibold opacity-90">{{ $message->sender->name }}</span>
                                <span class="text-xs opacity-75">{{ $message->created_at->format('Y-m-d H:i') }}</span>
                            </div>
                            @if($message->type === 'file')
                                <div class="flex items-center gap-3">
                                    <i class="fas fa-file text-xl"></i>
                                    <a href="{{ Storage::url($message->file_path) }}" target="_blank" 
                                       class="hover:underline font-medium">
                                        {{ $message->file_name ?? 'ملف' }}
                                    </a>
                                </div>
                            @endif
                            @if($message->message)
                                <p class="mt-2 whitespace-pre-wrap">{{ $message->message }}</p>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12 text-slate-500">
                        <i class="fas fa-comments text-4xl mb-3 opacity-50"></i>
                        <p>{{ __('admin.conversations.no_messages') }}</p>
                    </div>
                @endforelse
            </div>

            {{-- Send Message Form --}}
            @if($conversation->status === 'open')
                <form method="POST" action="{{ route('admin.conversations.send-message', $conversation) }}" 
                      enctype="multipart/form-data"
                      class="border-t border-slate-200 pt-6 mt-6">
                    @csrf
                    <div class="grid gap-4">
                        <div class="grid gap-2">
                            <label for="message" class="text-sm font-medium text-slate-600">
                                {{ __('admin.conversations.form.message') }}
                            </label>
                            <textarea id="message" name="message" rows="3"
                                      class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200"
                                      placeholder="{{ __('admin.conversations.form.message_placeholder') }}"></textarea>
                            @error('message')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="grid gap-2">
                            <label for="file" class="text-sm font-medium text-slate-600">
                                {{ __('admin.conversations.form.file') }}
                            </label>
                            <input id="file" name="file" type="file"
                                   class="rounded-xl border border-slate-200 bg-white px-4 py-2 text-sm text-slate-800 focus:border-indigo-400 focus:outline-none focus:ring-2 focus:ring-indigo-200">
                            <p class="text-xs text-slate-400">{{ __('admin.conversations.form.file_hint') }}</p>
                            @error('file')
                                <p class="text-xs text-rose-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="flex items-center justify-end gap-3">
                            <button type="submit"
                                    class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-6 py-3 text-sm font-semibold text-white shadow-md shadow-indigo-400/40 transition hover:bg-indigo-700">
                                <i class="fas fa-paper-plane"></i>
                                {{ __('admin.conversations.actions.send') }}
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="border-t border-slate-200 pt-6 mt-6 text-center text-slate-500">
                    <p>{{ __('admin.conversations.conversation_closed') }}</p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            // Auto scroll to bottom
            const messagesContainer = document.getElementById('messagesContainer');
            if (messagesContainer) {
                messagesContainer.scrollTop = messagesContainer.scrollHeight;
            }
        </script>
    @endpush
@endsection

