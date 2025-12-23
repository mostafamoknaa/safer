<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('admin.layout.default_title')) - {{ __('admin.layout.title_suffix') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" rel="stylesheet">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.min.css" rel="stylesheet">
        <script defer src="https://cdnjs.cloudflare.com/ajax/libs/trix/2.0.0/trix.umd.min.js"></script>
    @endif
    <style>
        [dir="rtl"] {
            font-family: 'Cairo', 'Tajawal', sans-serif;
        }
        .sidebar-mobile {
            transform: translateX(100%);
            transition: transform 0.35s ease;
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        .overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.35s ease;
        }
        .overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1d3b5c 55%, #0f172a 100%);
        }
        .sidebar-accent {
            background: radial-gradient(circle at top, rgba(56, 189, 248, 0.25), transparent 65%);
        }
    </style>
    <style>
        trix-editor {
            min-height: 200px;
            border-radius: 0.75rem;
            border: 1px solid rgb(226 232 240);
            background-color: white;
            padding-inline: 1rem;
            padding-block: 0.75rem;
            color: rgb(51 65 85);
        }

        trix-editor:focus {
            outline: none;
            border-color: rgb(99 102 241);
            box-shadow: 0 0 0 3px rgba(129, 140, 248, 0.25);
        }

        trix-toolbar .trix-button-group {
            border-radius: 0.75rem;
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800">
    @php
        use Illuminate\Support\Facades\Route as RouteFacade;

        $navItems = [
            [
                'route' => RouteFacade::has('admin.dashboard') ? route('admin.dashboard') : '#',
                'icon' => 'fas fa-gauge-high',
                'active' => request()->routeIs('admin.dashboard'),
                'label' => __('admin.nav.dashboard'),
            ],
            [
                'route' => RouteFacade::has('admin.bookings.index') ? route('admin.bookings.index') : '#',
                'icon' => 'fas fa-calendar-check',
                'active' => RouteFacade::has('admin.bookings.index') ? request()->routeIs('admin.bookings.*') : false,
                'label' => __('admin.nav.bookings'),
                'disabled' => ! RouteFacade::has('admin.bookings.index'),
            ],
            // [
            //     'route' => RouteFacade::has('admin.rooms.index') ? route('admin.rooms.index') : '#',
            //     'icon' => 'fas fa-bed',
            //     'active' => RouteFacade::has('admin.rooms.index') ? request()->routeIs('admin.rooms.*') : false,
            //     'label' => __('admin.nav.rooms'),
            //     'disabled' => ! RouteFacade::has('admin.rooms.index'),
            // ],
            // [
            //     'route' => RouteFacade::has('admin.guests.index') ? route('admin.guests.index') : '#',
            //     'icon' => 'fas fa-user-friends',
            //     'active' => RouteFacade::has('admin.guests.index') ? request()->routeIs('admin.guests.*') : false,
            //     'label' => __('admin.nav.guests'),
            //     'disabled' => ! RouteFacade::has('admin.guests.index'),
            // ],
            [
                'route' => RouteFacade::has('admin.payments.index') ? route('admin.payments.index') : '#',
                'icon' => 'fas fa-credit-card',
                'active' => RouteFacade::has('admin.payments.index') ? request()->routeIs('admin.payments.*') : false,
                'label' => __('admin.nav.payments'),
                'disabled' => ! RouteFacade::has('admin.payments.index'),
            ],
            [
                'route' => RouteFacade::has('admin.reports.bookings') ? route('admin.reports.bookings') : '#',
                'icon' => 'fas fa-chart-line',
                'active' => RouteFacade::has('admin.reports.bookings') ? request()->routeIs('admin.reports.*') : false,
                'label' => __('admin.nav.reports'),
                'disabled' => ! RouteFacade::has('admin.reports.bookings'),
            ],
            // [
            //     'route' => RouteFacade::has('admin.reports.index') ? route('admin.reports.index') : '#',
            //     'icon' => 'fas fa-chart-line',
            //     'active' => RouteFacade::has('admin.reports.index') ? request()->routeIs('admin.reports.*') : false,
            //     'label' => __('admin.nav.reports'),
            //     'disabled' => ! RouteFacade::has('admin.reports.index'),
            // ],
            // [
            //     'route' => RouteFacade::has('admin.settings.index') ? route('admin.settings.index') : '#',
            //     'icon' => 'fas fa-sliders',
            //     'active' => RouteFacade::has('admin.settings.index') ? request()->routeIs('admin.settings.*') : false,
            //     'label' => __('admin.nav.settings'),
            //     'disabled' => ! RouteFacade::has('admin.settings.index'),
            // ],
            [
                'route' => RouteFacade::has('admin.contact-links.index') ? route('admin.contact-links.index') : '#',
                'icon' => 'fas fa-envelope-open-text',
                'active' => RouteFacade::has('admin.contact-links.index') ? request()->routeIs('admin.contact-links.*') : false,
                'label' => __('admin.nav.contact'),
                'disabled' => ! RouteFacade::has('admin.contact-links.index'),
            ],
            [
                'route' => RouteFacade::has('admin.policies.index') ? route('admin.policies.index') : '#',
                'icon' => 'fas fa-shield-heart',
                'active' => RouteFacade::has('admin.policies.index') ? request()->routeIs('admin.policies.*') : false,
                'label' => __('admin.nav.privacy'),
                'disabled' => ! RouteFacade::has('admin.policies.index'),
            ],
            [
                'route' => RouteFacade::has('admin.faqs.index') ? route('admin.faqs.index') : '#',
                'icon' => 'fas fa-circle-question',
                'active' => RouteFacade::has('admin.faqs.index') ? request()->routeIs('admin.faqs.*') : false,
                'label' => __('admin.nav.faq'),
                'disabled' => ! RouteFacade::has('admin.faqs.index'),
            ],
            [
                'route' => RouteFacade::has('admin.hotels.index') ? route('admin.hotels.index') : '#',
                'icon' => 'fas fa-hotel',
                'active' => RouteFacade::has('admin.hotels.index') ? request()->routeIs('admin.hotels.*') : false,
                'label' => __('admin.nav.hotels'),
                'disabled' => ! RouteFacade::has('admin.hotels.index'),
            ],
            [
                'route' => RouteFacade::has('admin.users.index') ? route('admin.users.index') : '#',
                'icon' => 'fas fa-users',
                'active' => RouteFacade::has('admin.users.index') ? request()->routeIs('admin.users.*') : false,
                'label' => __('admin.nav.users'),
                'disabled' => ! RouteFacade::has('admin.users.index'),
            ],
            [
                'route' => RouteFacade::has('admin.conversations.index') ? route('admin.conversations.index') : '#',
                'icon' => 'fas fa-comments',
                'active' => RouteFacade::has('admin.conversations.index') ? request()->routeIs('admin.conversations.*') : false,
                'label' => __('admin.nav.conversations'),
                'disabled' => ! RouteFacade::has('admin.conversations.index'),
            ],
            [
                'route' => RouteFacade::has('admin.bookings.index') ? route('admin.bookings.index') : '#',
                'icon' => 'fas fa-calendar-check',
                'active' => RouteFacade::has('admin.bookings.index') ? request()->routeIs('admin.bookings.*') : false,
                'label' => __('admin.nav.bookings'),
                'disabled' => ! RouteFacade::has('admin.bookings.index'),
            ],
            [
                'route' => RouteFacade::has('admin.payments.index') ? route('admin.payments.index') : '#',
                'icon' => 'fas fa-credit-card',
                'active' => RouteFacade::has('admin.payments.index') ? request()->routeIs('admin.payments.*') : false,
                'label' => __('admin.nav.payments'),
                'disabled' => ! RouteFacade::has('admin.payments.index'),
            ],
            [
                'route' => RouteFacade::has('admin.buses.index') ? route('admin.buses.index') : '#',
                'icon' => 'fas fa-bus',
                'active' => RouteFacade::has('admin.buses.index') ? request()->routeIs('admin.buses.*') : false,
                'label' => __('admin.nav.buses'),
                'disabled' => ! RouteFacade::has('admin.buses.index'),
            ],
            [
                'route' => RouteFacade::has('admin.trips.index') ? route('admin.trips.index') : '#',
                'icon' => 'fas fa-route',
                'active' => RouteFacade::has('admin.trips.index') ? request()->routeIs('admin.trips.*') : false,
                'label' => __('admin.nav.trips'),
                'disabled' => ! RouteFacade::has('admin.trips.index'),
            ],
            [
                'route' => RouteFacade::has('admin.private-cars.index') ? route('admin.private-cars.index') : '#',
                'icon' => 'fas fa-car',
                'active' => RouteFacade::has('admin.private-cars.index') ? request()->routeIs('admin.private-cars.*') : false,
                'label' => __('admin.nav.private_cars'),
                'disabled' => ! RouteFacade::has('admin.private-cars.index'),
            ],
            [
                'route' => RouteFacade::has('admin.service-requests.index') ? route('admin.service-requests.index') : '#',
                'icon' => 'fas fa-clipboard-list',
                'active' => RouteFacade::has('admin.service-requests.index') ? request()->routeIs('admin.service-requests.*') : false,
                'label' => __('admin.nav.service_requests'),
                'disabled' => ! RouteFacade::has('admin.service-requests.index'),
            ],
            [
                'route' => RouteFacade::has('admin.events.index') ? route('admin.events.index') : '#',
                'icon' => 'fas fa-calendar-alt',
                'active' => RouteFacade::has('admin.events.index') ? request()->routeIs('admin.events.*') : false,
                'label' => __('admin.nav.events'),
                'disabled' => ! RouteFacade::has('admin.events.index'),
            ],
        ];
    @endphp

    <div id="mobileOverlay" class="overlay fixed inset-0 bg-slate-900/60 z-40 lg:hidden"></div>

    <div class="min-h-screen flex overflow-hidden relative">
        <div class="hidden lg:flex w-72 flex-col sidebar-gradient text-white relative">
            <div class="absolute inset-0 sidebar-accent opacity-80"></div>
            <div class="relative p-7 border-b border-white/10">
                <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-sky-100">
                    <i class="fas fa-sparkles text-sky-200"></i>
                    {{ __('admin.layout.subtitle_badge') }}
                </div>
                {{-- <h1 class="mt-4 text-2xl font-semibold text-white">{{ __('admin.layout.brand') }}</h1> --}}
                <p class="mt-2 text-sm text-sky-100/75 leading-relaxed">{{ __('admin.layout.tagline') }}</p>
            </div>
            <nav class="relative flex-1 overflow-y-auto px-4 py-6 space-y-1">
                <p class="px-3 text-xs font-semibold uppercase tracking-widest text-slate-200/50">
                    {{ __('admin.layout.nav_heading') }}
                </p>
                @foreach ($navItems as $item)
                    <a href="{{ $item['route'] }}"
                       @class([
                           'relative mt-2 flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200',
                           'bg-white/15 text-white shadow-lg shadow-sky-900/20 ring-1 ring-white/25 backdrop-blur' => $item['active'],
                           'text-slate-200 hover:text-white hover:bg-white/10' => ! $item['active'],
                           'opacity-60 pointer-events-none' => $item['disabled'] ?? false,
                       ])>
                        <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-lg">
                            <i class="{{ $item['icon'] }}"></i>
                        </span>
                        <span>{{ $item['label'] }}</span>
                        @if ($item['active'])
                            <span class="absolute inset-y-1 -left-1 w-1 rounded-full bg-sky-300"></span>
                        @endif
                    </a>
                @endforeach
            </nav>
            <div class="relative border-t border-white/10 p-6">
                <div class="rounded-2xl bg-white/10 p-5 backdrop-blur-md">
                    <div class="flex items-center gap-3 text-white">
                        <span class="inline-flex h-11 w-11 items-center justify-center rounded-xl bg-white/15 text-lg">
                            <i class="fas fa-headset"></i>
                        </span>
                        <div class="text-xs leading-relaxed">
                            <p class="font-semibold text-base">{{ __('admin.layout.session_title') }}</p>
                            <p class="text-sky-100/75">{{ __('admin.layout.session_hint') }}</p>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                        @csrf
                        <button type="submit"
                                class="w-full rounded-xl bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-lg shadow-sky-900/25 transition hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-white/50">
                            <i class="fas fa-right-from-bracket ms-2"></i>
                            {{ __('admin.layout.logout') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <div id="mobileSidebar" class="sidebar-mobile fixed top-0 right-0 h-full w-64 sidebar-gradient text-white z-50 lg:hidden shadow-2xl shadow-slate-900/40">
            <div class="p-6">
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h2 class="text-xl font-bold">{{ __('admin.layout.brand') }}</h2>
                        <p class="text-blue-100 text-sm">{{ __('admin.layout.tagline') }}</p>
                    </div>
                    <button id="closeMobileSidebar" class="text-white hover:text-sky-200 transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>
                <nav class="space-y-1">
                    @foreach ($navItems as $item)
                        <a href="{{ $item['route'] }}"
                           @class([
                               'flex items-center gap-3 rounded-lg px-4 py-3 text-sm font-medium transition',
                               'bg-white/15 text-white' => $item['active'],
                               'text-blue-100 hover:bg-white/10 hover:text-white' => ! $item['active'],
                               'opacity-60 pointer-events-none' => $item['disabled'] ?? false,
                           ])>
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-lg bg-white/10 text-lg">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </nav>
            </div>
        </div>

        <div class="flex-1 flex flex-col min-w-0 relative">
            <header class="relative border-b border-slate-200/70 bg-white/80 backdrop-blur">
                <div class="absolute inset-0 pointer-events-none bg-gradient-to-l from-sky-100/45 via-transparent to-sky-50/30"></div>
                <div class="relative px-5 sm:px-8 py-5 flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                    <div class="flex items-start gap-4">
                        <button id="openMobileSidebar" class="lg:hidden text-slate-600 hover:text-sky-600 transition">
                            <span class="sr-only">{{ __('admin.layout.open_menu') }}</span>
                            <i class="fas fa-bars text-2xl"></i>
                        </button>
                        <div>
                            <div class="inline-flex items-center gap-2 rounded-full border border-sky-200 bg-sky-50 px-3 py-1 text-xs font-semibold text-sky-700">
                                <span class="inline-flex h-2 w-2 rounded-full bg-sky-500 shadow shadow-sky-200"></span>
                                {{ __('admin.layout.live_indicator') }}
                            </div>
                            <h1 class="mt-3 text-3xl font-semibold text-slate-900 tracking-tight">
                                @yield('page-title', __('admin.layout.default_heading'))
                            </h1>
                            <p class="mt-1 text-sm text-slate-500">
                                @yield('page-subtitle', __('admin.layout.default_subheading'))
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-4">
                        <div class="hidden sm:flex flex-col text-sm text-right text-slate-600">
                            <span class="font-semibold text-slate-900">{{ __('admin.layout.greeting', ['name' => auth()->user()?->name]) }}</span>
                            <span class="text-xs text-slate-500">{{ now()->translatedFormat('l d F Y') }}</span>
                        </div>
                        <div class="flex items-center gap-3 rounded-2xl border border-slate-200 bg-white px-3 py-2 shadow-sm">
                            <span class="flex h-10 w-10 items-center justify-center rounded-xl bg-sky-500/10 text-sky-500 text-lg">
                                <i class="fas fa-user-tie"></i>
                            </span>
                            <span class="text-sm font-semibold text-slate-700 sm:hidden">
                                {{ __('admin.layout.greeting_short', ['name' => \Illuminate\Support\Str::limit(auth()->user()?->name, 12)]) }}
                            </span>
                            <form method="POST" action="{{ route('admin.logout') }}">
                                @csrf
                                <button type="submit"
                                        class="inline-flex items-center gap-1.5 rounded-xl bg-slate-900 px-3 py-1.5 text-xs font-semibold text-white transition hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-400/50">
                                    <i class="fas fa-right-from-bracket"></i>
                                    {{ __('admin.layout.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <main class="relative flex-1 overflow-y-auto">
                <div class="absolute inset-0 -z-10 bg-gradient-to-br from-slate-100 via-white to-sky-100/35"></div>
                <div class="relative px-5 py-6 sm:px-8 lg:px-10">


                    <div class="mt-8 space-y-4">
                        @if(session('success'))
                            <div class="rounded-2xl border border-emerald-200/80 bg-emerald-50/80 px-5 py-4 text-sm text-emerald-700 shadow-sm shadow-emerald-200/40">
                                <i class="fas fa-circle-check ms-2"></i>
                                {{ session('success') }}
                            </div>
                        @endif
                        @if(session('error'))
                            <div class="rounded-2xl border border-rose-200/80 bg-rose-50/80 px-5 py-4 text-sm text-rose-700 shadow-sm shadow-rose-200/40">
                                <i class="fas fa-circle-exclamation ms-2"></i>
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    <div class="mt-6">
                        @yield('content')
                    </div>
                </div>
            </main>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const openMobileSidebar = document.getElementById('openMobileSidebar');
            const closeMobileSidebar = document.getElementById('closeMobileSidebar');
            const mobileSidebar = document.getElementById('mobileSidebar');
            const mobileOverlay = document.getElementById('mobileOverlay');

            function openSidebar() {
                mobileSidebar.classList.add('open');
                mobileOverlay.classList.add('show');
                document.body.style.overflow = 'hidden';
            }

            function closeSidebar() {
                mobileSidebar.classList.remove('open');
                mobileOverlay.classList.remove('show');
                document.body.style.overflow = 'auto';
            }

            openMobileSidebar?.addEventListener('click', openSidebar);
            closeMobileSidebar?.addEventListener('click', closeSidebar);
            mobileOverlay?.addEventListener('click', closeSidebar);

            mobileSidebar?.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', closeSidebar);
            });

            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && mobileSidebar.classList.contains('open')) {
                    closeSidebar();
                }
            });
        });
    </script>
    @stack('scripts')
</body>
</html>

