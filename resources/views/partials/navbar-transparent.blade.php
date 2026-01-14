<!-- Navbar for Home Page (Transparent) -->
<nav class="absolute top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-6">

    <div class="flex items-center gap-2">
        <div class="w-10 h-10 flex items-center justify-center rounded-lg">
            <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo"
                class="w-full h-full object-contain">
        </div>
    </div>
    <div class="hidden lg:flex items-center gap-8 text-white">
        <a href="{{ route('web.home') }}"
            class="hover:opacity-80 transition {{ request()->routeIs('web.home') ? 'text-safer-blue font-bold border-b-2 border-safer-blue' : '' }}">الرئيسية</a>
        <a href="{{ route('web.hotels.index') }}"
            class="hover:opacity-80 transition {{ request()->routeIs('web.hotels.*') ? 'text-safer-blue font-bold border-b-2 border-safer-blue' : '' }}">الإقامات</a>
        <!-- Services Dropdown -->
        <div class="relative group">
            <button
                class="flex items-center gap-1 hover:opacity-80 transition {{ request()->routeIs('web.services.*') || request()->routeIs('web.private_cars.*') || request()->routeIs('web.buses.*') ? 'text-safer-blue font-bold' : '' }}">
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
            class="hover:opacity-80 transition {{ request()->routeIs('web.events.*') || request()->routeIs('web.services.trips*') ? 'text-safer-blue font-bold border-b-2 border-safer-blue' : '' }}">الأنشطة</a>
        <a href="{{ route('web.contact') }}"
            class="hover:opacity-80 transition {{ request()->routeIs('web.contact') ? 'text-safer-blue font-bold border-b-2 border-safer-blue' : '' }}">تواصل
            معنا</a>
    </div>

    <div class="flex items-center gap-4">
        @auth
            <div class="relative group">
                <button
                    class="flex items-center gap-3 bg-white/10 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/20 hover:bg-white/20 transition">
                    <span class="text-white font-bold">{{ auth()->user()->name }}</span>
                    <div
                        class="w-10 h-10 rounded-full bg-safer-blue flex items-center justify-center text-white overflow-hidden shadow-lg border-2 border-white/30">
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
                class="bg-safer-blue text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                تسجيل دخول
            </a>
        @endauth
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