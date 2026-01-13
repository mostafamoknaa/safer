<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', __('hotel.layout.default_title')) - {{ __('hotel.layout.title_suffix') }}</title>
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
            transition: transform 0.3s ease-in-out;
        }
        .sidebar-mobile.open {
            transform: translateX(0);
        }
        .overlay {
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        .overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .sidebar-gradient {
            background: linear-gradient(180deg, #0f172a 0%, #1e293b 50%, #0f172a 100%);
        }
        .sidebar-accent {
            background: radial-gradient(circle at top, rgba(59, 130, 246, 0.15), transparent 70%);
        }
        @media (max-width: 1023px) {
            .main-content {
                width: 100%;
            }
        }
        @media (min-width: 1024px) {
            .main-content {
                margin-right: 288px;
            }
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 antialiased">
    @php
        use Illuminate\Support\Facades\Route as RouteFacade;

        $navItems = [
            [
                'route' => RouteFacade::has('hotel.dashboard') ? route('hotel.dashboard') : '#',
                'icon' => 'fas fa-gauge-high',
                'active' => request()->routeIs('hotel.dashboard'),
                'label' => __('hotel.nav.dashboard'),
            ],
            [
                'route' => RouteFacade::has('hotel.hotels.index') ? route('hotel.hotels.index') : '#',
                'icon' => 'fas fa-hotel',
                'active' => RouteFacade::has('hotel.hotels.index') ? request()->routeIs('hotel.hotels.*') : false,
                'label' => __('hotel.nav.hotels'),
                'disabled' => ! RouteFacade::has('hotel.hotels.index'),
            ],
            [
                'route' => RouteFacade::has('hotel.hotel-rooms.index') ? route('hotel.hotel-rooms.index') : '#',
                'icon' => 'fas fa-bed',
                'active' => RouteFacade::has('hotel.hotel-rooms.index') ? request()->routeIs('hotel.hotel-rooms.*') : false,
                'label' => __('hotel.nav.rooms'),
                'disabled' => ! RouteFacade::has('hotel.hotel-rooms.index'),
            ],
            [
                'route' => RouteFacade::has('hotel.conversations.index') ? route('hotel.conversations.index') : '#',
                'icon' => 'fas fa-comments',
                'active' => RouteFacade::has('hotel.conversations.index') ? request()->routeIs('hotel.conversations.*') : false,
                'label' => __('hotel.nav.conversations'),
                'disabled' => ! RouteFacade::has('hotel.conversations.index'),
            ],
            [
                'route' => RouteFacade::has('hotel.bookings.index') ? route('hotel.bookings.index') : '#',
                'icon' => 'fas fa-calendar-check',
                'active' => RouteFacade::has('hotel.bookings.index') ? request()->routeIs('hotel.bookings.*') : false,
                'label' => __('hotel.nav.bookings'),
                'disabled' => ! RouteFacade::has('hotel.bookings.index'),
            ],
            [
                'route' => RouteFacade::has('hotel.payments.index') ? route('hotel.payments.index') : '#',
                'icon' => 'fas fa-money-bill-wave',
                'active' => RouteFacade::has('hotel.payments.index') ? request()->routeIs('hotel.payments.*') : false,
                'label' => __('hotel.nav.payments'),
                'disabled' => ! RouteFacade::has('hotel.payments.index'),
            ],
            [
                'route' => RouteFacade::has('hotel.reports.bookings') ? route('hotel.reports.bookings') : '#',
                'icon' => 'fas fa-chart-line',
                'active' => RouteFacade::has('hotel.reports.bookings') ? request()->routeIs('hotel.reports.*') : false,
                'label' => __('hotel.nav.reports'),
                'disabled' => ! RouteFacade::has('hotel.reports.bookings'),
            ],
        ];
    @endphp

    <!-- Mobile Overlay -->
    <div id="mobileOverlay" class="overlay fixed inset-0 bg-slate-900/60 z-40 lg:hidden"></div>

    <div class="flex min-h-screen">
        <!-- Desktop Sidebar -->
        <aside id="desktopSidebar" class="hidden lg:flex fixed inset-y-0 right-0 w-72 flex-col sidebar-gradient text-white z-30">
            <div class="absolute inset-0 sidebar-accent opacity-80"></div>
            <div class="relative flex flex-col h-full">
                <!-- Logo Section -->
                <div class="p-6 border-b border-white/10">
                    <div class="inline-flex items-center gap-2 rounded-full bg-white/10 px-3 py-1 text-xs font-semibold uppercase tracking-wider text-sky-100 mb-4">
                        <i class="fas fa-sparkles text-sky-200"></i>
                        {{ __('hotel.layout.brand') }}
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-300 shadow-lg">
                            <i class="fas fa-hotel text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-white">{{ __('hotel.layout.brand') }}</h1>
                            <p class="text-xs text-slate-300 mt-1">{{ __('hotel.layout.tagline') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Navigation -->
                <nav class="relative flex-1 overflow-y-auto min-h-0 px-4 py-6 space-y-2">
                    <p class="px-3 mb-3 text-xs font-semibold uppercase tracking-widest text-slate-200/50">
                        {{ __('hotel.layout.nav_heading') }}
                    </p>
                    @foreach ($navItems as $item)
                        <a href="{{ $item['route'] }}"
                           @class([
                               'relative flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200',
                               'bg-white/15 text-white shadow-lg shadow-sky-900/20 ring-1 ring-white/25 backdrop-blur' => $item['active'],
                               'text-slate-200 hover:text-white hover:bg-white/10' => ! $item['active'] && !($item['disabled'] ?? false),
                               'opacity-60 pointer-events-none cursor-not-allowed' => $item['disabled'] ?? false,
                           ])>
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-lg shrink-0">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if ($item['active'])
                                <span class="absolute inset-y-2 -left-1 w-1 rounded-full bg-sky-300 shadow-lg shadow-sky-300/50"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <!-- User & Logout Section -->
                <div class="relative border-t border-white/10 p-6">
                    <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-md border border-white/10">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-300 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                        <form method="POST" action="{{ route('hotel.logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full rounded-xl bg-white/20 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-white/30 focus:outline-none focus:ring-2 focus:ring-white/50 backdrop-blur-sm border border-white/20">
                                <i class="fas fa-sign-out-alt ml-2"></i>
                                {{ __('hotel.layout.logout') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar -->
        <aside id="mobileSidebar" class="sidebar-mobile fixed inset-y-0 right-0 z-50 w-72 bg-slate-900 text-white lg:hidden shadow-2xl">
            <div class="flex h-full flex-col sidebar-gradient">
                <div class="flex items-center justify-between border-b border-white/10 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-indigo-500/20 text-indigo-300">
                            <i class="fas fa-hotel text-xl"></i>
                        </div>
                        <div>
                            <h1 class="text-lg font-semibold text-white">{{ __('hotel.layout.brand') }}</h1>
                            <p class="text-xs text-slate-300">{{ __('hotel.layout.tagline') }}</p>
                        </div>
                    </div>
                    <button id="closeMobileSidebar" class="text-slate-400 hover:text-white transition">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <nav class="relative flex-1 overflow-y-auto min-h-0 px-4 py-6 space-y-2">
                    <p class="px-3 mb-3 text-xs font-semibold uppercase tracking-widest text-slate-200/50">
                        {{ __('hotel.layout.nav_heading') }}
                    </p>
                    @foreach ($navItems as $item)
                        <a href="{{ $item['route'] }}"
                           @class([
                               'relative flex items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium transition-all duration-200',
                               'bg-white/15 text-white shadow-lg' => $item['active'],
                               'text-slate-200 hover:text-white hover:bg-white/10' => ! $item['active'] && !($item['disabled'] ?? false),
                               'opacity-60 pointer-events-none' => $item['disabled'] ?? false,
                           ])
                           onclick="closeMobileSidebar()">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-lg shrink-0">
                                <i class="{{ $item['icon'] }}"></i>
                            </span>
                            <span class="flex-1">{{ $item['label'] }}</span>
                            @if ($item['active'])
                                <span class="absolute inset-y-2 -left-1 w-1 rounded-full bg-sky-300"></span>
                            @endif
                        </a>
                    @endforeach
                </nav>

                <div class="relative border-t border-white/10 p-6">
                    <div class="rounded-xl bg-white/10 p-4 backdrop-blur-md mb-4">
                        <div class="flex items-center gap-3">
                            <div class="flex h-10 w-10 items-center justify-center rounded-full bg-white/15 text-white">
                                <i class="fas fa-user"></i>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-slate-300 truncate">{{ auth()->user()->email }}</p>
                            </div>
                        </div>
                    </div>
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit"
                                class="flex w-full items-center gap-3 rounded-xl px-4 py-3 text-sm font-medium text-slate-200 transition hover:bg-white/10 hover:text-white">
                            <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-white/10 text-lg">
                                <i class="fas fa-sign-out-alt"></i>
                            </span>
                            <span>{{ __('hotel.layout.logout') }}</span>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="main-content flex flex-1 flex-col min-w-0">
            <!-- Header -->
            <header class="sticky top-0 z-20 flex items-center justify-between border-b border-slate-200 bg-white/95 backdrop-blur-sm px-4 py-4 sm:px-6 lg:px-8 shadow-sm">
                <div class="flex items-center gap-4 flex-1 min-w-0">
                    <button id="openMobileSidebar" class="lg:hidden text-slate-600 hover:text-slate-900 transition p-2 rounded-lg hover:bg-slate-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <div class="flex-1 min-w-0">
                        <h1 class="text-xl font-bold text-slate-900 truncate">@yield('page-title', __('hotel.layout.default_heading'))</h1>
                        <p class="text-sm text-slate-500 truncate mt-0.5">@yield('page-subtitle', __('hotel.layout.default_subheading'))</p>
                    </div>
                </div>
                <div class="hidden sm:flex items-center gap-4 ml-4">
                    <div class="flex items-center gap-2 px-3 py-2 rounded-lg bg-slate-100 text-sm text-slate-600">
                        <i class="fas fa-user-circle"></i>
                        <span class="font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('hotel.logout') }}">
                        @csrf
                        <button type="submit" class="flex items-center gap-2 px-3 py-2 rounded-lg bg-rose-50 text-sm text-rose-600 hover:bg-rose-100 transition shadow-sm border border-rose-100">
                            <i class="fas fa-sign-out-alt"></i>
                            <span class="font-semibold">{{ __('hotel.layout.logout') }}</span>
                        </button>
                    </form>
                </div>
            </header>

            <!-- Content -->
            <main class="flex-1 overflow-x-hidden">
                <div class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto w-full">
                    @if (session('success'))
                        <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700 shadow-sm animate-slide-down">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-check-circle"></i>
                                <span>{{ session('success') }}</span>
                            </div>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-700 shadow-sm animate-slide-down">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-exclamation-circle"></i>
                                <span>{{ session('error') }}</span>
                            </div>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script>
        const openMobileSidebar = document.getElementById('openMobileSidebar');
        const closeMobileSidebarBtn = document.getElementById('closeMobileSidebar');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const overlay = document.getElementById('mobileOverlay');

        function openMobileSidebarFunc() {
            if (mobileSidebar) {
                mobileSidebar.classList.add('open');
            }
            if (overlay) {
                overlay.classList.add('show');
            }
            document.body.style.overflow = 'hidden';
        }

        function closeMobileSidebar() {
            if (mobileSidebar) {
                mobileSidebar.classList.remove('open');
            }
            if (overlay) {
                overlay.classList.remove('show');
            }
            document.body.style.overflow = '';
        }

        if (openMobileSidebar) {
            openMobileSidebar.addEventListener('click', openMobileSidebarFunc);
        }

        if (closeMobileSidebarBtn) {
            closeMobileSidebarBtn.addEventListener('click', closeMobileSidebar);
        }

        if (overlay) {
            overlay.addEventListener('click', closeMobileSidebar);
        }

        // Close mobile sidebar on route change
        document.addEventListener('DOMContentLoaded', function() {
            const navLinks = document.querySelectorAll('#mobileSidebar a[href]');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    setTimeout(closeMobileSidebar, 100);
                });
            });
        });
    </script>

    @stack('scripts')
</body>
</html>
