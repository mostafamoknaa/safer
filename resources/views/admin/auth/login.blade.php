<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __('admin.auth.page_title') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-100 text-slate-800">
    <div class="flex min-h-screen items-center justify-center px-4 py-10">
        <div class="w-full max-w-md rounded-2xl bg-white p-8 shadow-xl ring-1 ring-slate-200/70">
            <div class="text-center">
                <span class="inline-flex items-center justify-center rounded-full bg-indigo-100 px-3 py-1 text-sm font-semibold text-indigo-600">
                    {{ __('admin.auth.badge') }}
                </span>
                <h1 class="mt-4 text-2xl font-semibold text-slate-900">{{ __('admin.auth.heading') }}</h1>
                <p class="mt-2 text-sm text-slate-500">{{ __('admin.auth.subheading') }}</p>
            </div>

            @if ($errors->any())
                <div class="mt-6 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700">
                    <ul class="space-y-1 text-right">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label for="email" class="block text-sm font-medium text-slate-700">
                        {{ __('admin.auth.email_label') }}
                    </label>
                    <input
                        type="email"
                        name="email"
                        id="email"
                        required
                        autofocus
                        autocomplete="email"
                        class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        placeholder="{{ __('admin.auth.email_placeholder') }}"
                        value="{{ old('email') }}"
                    >
                </div>
                <div class="space-y-2">
                    <label for="password" class="block text-sm font-medium text-slate-700">
                        {{ __('admin.auth.password_label') }}
                    </label>
                    <input
                        type="password"
                        name="password"
                        id="password"
                        required
                        autocomplete="current-password"
                        class="block w-full rounded-lg border border-slate-300 bg-slate-50 px-4 py-2 text-sm text-slate-900 focus:border-indigo-500 focus:bg-white focus:outline-none focus:ring-2 focus:ring-indigo-200"
                        placeholder="{{ __('admin.auth.password_placeholder') }}"
                    >
                </div>
                <div class="flex items-center justify-between">
                    <label for="remember" class="flex items-center gap-2 text-sm text-slate-600">
                        <input
                            type="checkbox"
                            name="remember"
                            id="remember"
                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500"
                            {{ old('remember') ? 'checked' : '' }}
                        >
                        <span>{{ __('admin.auth.remember') }}</span>
                    </label>
                    <span class="text-xs text-slate-400">{{ __('admin.auth.support_hint') }}</span>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2"
                >
                    {{ __('admin.auth.submit') }}
                </button>
            </form>
        </div>
    </div>
</body>
</html>

