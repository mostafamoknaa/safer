<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Safer - نظام الحجوزات')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white shadow-lg">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <div class="flex items-center space-x-4">
                    <a href="{{ route('web.home') }}" class="text-2xl font-bold text-blue-600">
                        Safer
                    </a>
                </div>

                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('web.home') }}" class="text-gray-700 hover:text-blue-600">
                        {{ __('الرئيسية') }}
                    </a>
                    <a href="{{ route('web.hotels.index') }}" class="text-gray-700 hover:text-blue-600">
                        {{ __('الفنادق') }}
                    </a>
                    <a href="{{ route('web.events.index') }}" class="text-gray-700 hover:text-blue-600">
                        {{ __('الفعاليات') }}
                    </a>
                    <a href="{{ route('web.services.buses') }}" class="text-gray-700 hover:text-blue-600">
                        {{ __('الخدمات') }}
                    </a>

                    @auth
                        <a href="{{ route('web.bookings.index') }}" class="text-gray-700 hover:text-blue-600">
                            {{ __('حجوزاتي') }}
                        </a>
                        <a href="{{ route('web.conversations.index') }}" class="text-gray-700 hover:text-blue-600">
                            {{ __('المحادثات') }}
                        </a>
                        <form method="POST" action="{{ route('admin.logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-gray-700 hover:text-blue-600">
                                {{ __('تسجيل الخروج') }}
                            </button>
                        </form>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600">
                            {{ __('تسجيل الدخول') }}
                        </a>
                    @endauth
                </div>

                <!-- Mobile menu button -->
                <button class="md:hidden" id="mobile-menu-button">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>

            <!-- Mobile menu -->
            <div class="md:hidden hidden" id="mobile-menu">
                <div class="py-2 space-y-2">
                    <a href="{{ route('web.home') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        {{ __('الرئيسية') }}
                    </a>
                    <a href="{{ route('web.hotels.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        {{ __('الفنادق') }}
                    </a>
                    <a href="{{ route('web.events.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        {{ __('الفعاليات') }}
                    </a>
                    <a href="{{ route('web.services.buses') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                        {{ __('الخدمات') }}
                    </a>
                    @auth
                        <a href="{{ route('web.bookings.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            {{ __('حجوزاتي') }}
                        </a>
                        <a href="{{ route('web.conversations.index') }}"
                            class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                            {{ __('المحادثات') }}
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        </div>
    @endif

    @if($errors->any())
        <div class="container mx-auto px-4 mt-4">
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <main class="py-8">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white mt-12">
        <div class="container mx-auto px-4 py-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Safer</h3>
                    <p class="text-gray-400">نظام متكامل لحجز الفنادق والفعاليات والخدمات</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">روابط سريعة</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('web.hotels.index') }}" class="text-gray-400 hover:text-white">الفنادق</a>
                        </li>
                        <li><a href="{{ route('web.events.index') }}"
                                class="text-gray-400 hover:text-white">الفعاليات</a></li>
                        <li><a href="{{ route('web.services.buses') }}"
                                class="text-gray-400 hover:text-white">الخدمات</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">تواصل معنا</h4>
                    <p class="text-gray-400">للاستفسارات والدعم</p>
                    @auth
                        <a href="{{ route('web.conversations.index') }}" class="text-blue-400 hover:text-blue-300">
                            تواصل معنا
                        </a>
                    @endauth
                </div>
            </div>
            <div class="border-t border-gray-700 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; {{ date('Y') }} Safer. جميع الحقوق محفوظة.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobile-menu-button')?.addEventListener('click', function () {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>

    @stack('scripts')
</body>

</html>