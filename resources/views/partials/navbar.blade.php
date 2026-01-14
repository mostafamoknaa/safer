<!-- Navbar -->
<nav class="bg-white shadow-sm px-8 py-4 sticky top-0 z-50">
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-2">
            <div class="w-10 h-10 flex items-center justify-center rounded-lg">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo"
                    class="w-full h-full object-contain">
            </div>
        </div>
        
        <div class="hidden lg:flex items-center gap-8 text-gray-700">
            <a href="{{ route('web.home') }}"
                class="hover:text-blue-600 transition {{ request()->routeIs('web.home') ? 'text-safer-blue font-bold' : '' }}">الرئيسية</a>
            <a href="{{ route('web.hotels.index') }}" 
                class="hover:text-blue-600 transition {{ request()->routeIs('web.hotels.*') ? 'text-safer-blue font-bold' : '' }}">الإقامات</a>
            <!-- Services Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-blue-600 transition {{ request()->routeIs('web.services.*') || request()->routeIs('web.private_cars.*') || request()->routeIs('web.buses.*') ? 'text-safer-blue font-bold' : '' }}">
                    <span>الخدمات</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div
                    class="absolute top-full right-0 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50">
                    <a href="{{ route('web.hotels.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-t-xl text-right">
                        حجز فنادق
                    </a>
                    <a href="{{ route('web.private_cars.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition text-right">
                        حجز سيارات خاصة
                    </a>
                    <a href="{{ route('web.buses.search') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-b-xl text-right">
                        حجز حافلة
                    </a>
                </div>
            </div>
            <a href="{{ route('web.events.index') }}"
                class="hover:text-blue-600 transition {{ request()->routeIs('web.events.*') || request()->routeIs('web.services.trips*') ? 'text-safer-blue font-bold' : '' }}">الأنشطة</a>
            <a href="{{ route('web.contact') }}" 
                class="hover:text-blue-600 transition {{ request()->routeIs('web.contact') ? 'text-safer-blue font-bold' : '' }}">تواصل معنا</a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <div class="relative group hidden lg:block">
                    <button
                        class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100 hover:bg-gray-100 transition">
                        <span class="text-gray-900 font-bold">{{ auth()->user()->name }}</span>
                        <div
                            class="w-10 h-10 rounded-full bg-safer-blue flex items-center justify-center text-white overflow-hidden shadow-lg border-2 border-white">
                            @if(auth()->user()->image)
                                <img src="{{ auth()->user()->image }}" alt="User" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                        class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-3 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[100] text-right">
                        <a href="{{ route('web.profile.edit') }}"
                            class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                            <span>الملف الشخصي</span>
                            <i class="fa-solid fa-user-pen text-sm"></i>
                        </a>
                        <a href="{{ route('web.favorites.index') }}"
                            class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                            <span>المفضلة</span>
                            <i class="fa-solid fa-heart text-sm"></i>
                        </a>
                        <a href="{{ route('web.bookings.index') }}"
                            class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition border-b border-gray-50">
                            <span>حجوزاتي</span>
                            <i class="fa-solid fa-calendar-check text-sm"></i>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-end gap-3 px-6 py-3 text-red-500 hover:bg-red-50 transition">
                                <span>تسجيل خروج</span>
                                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="bg-safer-blue text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition hidden lg:block">
                    تسجيل دخول
                </a>
            @endauth

            <!-- Mobile menu button -->
            <button class="lg:hidden text-gray-700" id="mobile-menu-button">
                <i class="fa-solid fa-bars text-2xl"></i>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div class="lg:hidden hidden mt-4 pb-4" id="mobile-menu">
        <div class="space-y-2">
            <a href="{{ route('web.home') }}" 
                class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg {{ request()->routeIs('web.home') ? 'bg-blue-50 text-blue-600 font-bold' : '' }}">
                الرئيسية
            </a>
            <a href="{{ route('web.hotels.index') }}" 
                class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg {{ request()->routeIs('web.hotels.*') ? 'bg-blue-50 text-blue-600 font-bold' : '' }}">
                الإقامات
            </a>
            
            <!-- Mobile Services Submenu -->
            <div>
                <button class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg" onclick="toggleMobileSubmenu()">
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                    <span>الخدمات</span>
                </button>
                <div class="hidden mr-4 mt-1 space-y-1" id="mobile-submenu">
                    <a href="{{ route('web.hotels.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg text-sm">
                        حجز فنادق
                    </a>
                    <a href="{{ route('web.private_cars.index') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg text-sm">
                        حجز سيارات خاصة
                    </a>
                    <a href="{{ route('web.buses.search') }}" class="block px-4 py-2 text-gray-600 hover:bg-gray-50 rounded-lg text-sm">
                        حجز حافلة
                    </a>
                </div>
            </div>

            <a href="{{ route('web.events.index') }}" 
                class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg {{ request()->routeIs('web.events.*') ? 'bg-blue-50 text-blue-600 font-bold' : '' }}">
                الأنشطة
            </a>
            <a href="{{ route('web.contact') }}" 
                class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg {{ request()->routeIs('web.contact') ? 'bg-blue-50 text-blue-600 font-bold' : '' }}">
                تواصل معنا
            </a>

            @auth
                <div class="border-t border-gray-200 pt-2 mt-2">
                    <a href="{{ route('web.profile.edit') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                        <i class="fa-solid fa-user-pen ml-2"></i>
                        الملف الشخصي
                    </a>
                    <a href="{{ route('web.favorites.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                        <i class="fa-solid fa-heart ml-2"></i>
                        المفضلة
                    </a>
                    <a href="{{ route('web.bookings.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                        <i class="fa-solid fa-calendar-check ml-2"></i>
                        حجوزاتي
                    </a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-right px-4 py-3 text-red-500 hover:bg-red-50 rounded-lg">
                            <i class="fa-solid fa-right-from-bracket ml-2"></i>
                            تسجيل خروج
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}" class="block px-4 py-3 bg-safer-blue text-white text-center rounded-lg font-bold">
                    تسجيل دخول
                </a>
            @endauth
        </div>
    </div>
</nav>

<style>
    .text-safer-blue {
        color: #2C67FF;
    }
    .bg-safer-blue {
        background-color: #2C67FF;
    }
</style>

<script>
    // Mobile menu toggle
    document.getElementById('mobile-menu-button')?.addEventListener('click', function() {
        document.getElementById('mobile-menu')?.classList.toggle('hidden');
    });

    // Mobile submenu toggle
    function toggleMobileSubmenu() {
        document.getElementById('mobile-submenu')?.classList.toggle('hidden');
    }
</script>