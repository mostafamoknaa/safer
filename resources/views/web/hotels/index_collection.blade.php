<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | {{ $title }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
        }

        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .hotel-card {
            transition: all 0.3s ease;
        }

        .rating-badge {
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navbar (Simplified/Reuse) -->
    <nav class="bg-white px-8 py-4 shadow-sm flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <a href="{{ route('web.home') }}" class="w-10 h-10 flex items-center justify-center rounded-lg">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo"
                    class="w-full h-full object-contain">
            </a>
        </div>
        <div class="hidden lg:flex items-center gap-8 text-gray-600 font-semibold">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600 transition">الرئيسية</a>
            <a href="{{ route('web.hotels.index') }}" class="hover:text-blue-600 transition">الإقامات</a>
            <!-- Services Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-blue-600 transition">
                    <span>الخدمات</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div
                    class="absolute top-full right-0 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 text-right">
                    <a href="{{ route('web.hotels.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-t-xl">
                        حجز فنادق
                    </a>
                    <a href="{{ route('web.private_cars.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-b-xl">
                        حجز سيارات خاصة
                    </a>
                </div>
            </div>
            <a href="{{ route('web.events.index') }}" class="hover:text-blue-600 transition">الأنشطة</a>
            <a href="{{ route('web.contact') }}" class="hover:text-blue-600 transition">تواصل معنا</a>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <div class="relative group">
                    <button
                        class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100 hover:bg-gray-100 transition">
                        <span class="text-gray-900 font-bold">{{ auth()->user()->name }}</span>
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white overflow-hidden shadow-lg border-2 border-white">
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
                        <a href="{{ route('web.profile.edit') }}" class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
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
                    class="bg-blue-600 text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                    تسجيل دخول
                </a>
            @endauth
        </div>
    </nav>

    <!-- Content -->
    <main class="py-12 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-gray-900 border-r-4 border-blue-600 pr-4">{{ $title }}</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach($hotels as $hotel)
                <div
                    class="hotel-card relative border border-gray-100 rounded-[32px] overflow-hidden bg-white block shadow-sm">
                    <div class="relative">
                        <a href="{{ route('web.hotels.show', $hotel->id) }}">
                            <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500' }}"
                                alt="{{ $hotel->name_ar }}" class="w-full h-64 object-cover">
                        </a>
                        <button
                            class="favorite-btn absolute top-4 right-4 w-9 h-9 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition shadow-sm z-10"
                            data-hotel-id="{{ $hotel->id }}">
                            <i
                                class="fa-solid fa-heart {{ auth()->check() && auth()->user()->favorites()->where('favoritable_id', $hotel->id)->exists() ? 'text-red-500' : '' }}"></i>
                        </button>
                        <div
                            class="absolute top-4 left-4 rating-badge flex items-center gap-1 text-xs font-bold text-white px-3 py-1.5 rounded-xl">
                            <i class="fa-solid fa-star text-yellow-400"></i>
                            {{ number_format($hotel->rate ?? 4.5, 1) }}
                        </div>
                    </div>
                    <div class="p-5 text-right">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">{{ $hotel->name_ar }} /
                            {{ $hotel->province ? $hotel->province->name_ar : 'مصر' }}
                        </h3>
                        <p class="text-blue-600 font-bold mb-1">
                            {{ $hotel->rooms->min('price_per_night') ? number_format($hotel->rooms->min('price_per_night')) : '1200' }}
                            جنيه / ليلة
                        </p>
                        <div class="flex gap-1 text-yellow-400 justify-end">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star {{ $i < round($hotel->rate ?? 5) ? '' : 'opacity-20' }}"></i>
                            @endfor
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-16 flex justify-center">
            {{ $hotels->links() }}
        </div>
    </main>

    <!-- Footer (Consistent) -->
    <footer class="bg-[#0A1124] text-white pt-20 pb-10 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-right">
            <div>
                <div class="flex items-center justify-start gap-2 mb-6">
                    <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" class="w-20 h-20 bg-white">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-8">موقع سياحي متكامل يساعدك على التخطيط لرحلتك
                    بسهولة...</p>
                <div class="flex justify-end gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                        class="w-32">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                        class="w-32">
                </div>
            </div>
            <div>
                <h3 class="font-bold text-xl mb-6">روابط سريعة</h3>
                <ul class="space-y-4 text-gray-400">
                    <li>الرئيسية</li>
                    <li>الإقامات</li>
                    <li>الخدمات</li>
                    <li>الانشطة</li>
                    <li>اتصل بنا</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-xl mb-6">تواصل معنا</h3>
                <ul class="space-y-4 text-gray-400">
                    <li class="flex items-center justify-end gap-3"><span>مصر</span><i
                            class="fa-solid fa-location-dot"></i></li>
                    <li class="flex items-center justify-end gap-3"><span>support@alfosafr.com</span><i
                            class="fa-solid fa-envelope"></i></li>
                    <li class="flex items-center justify-end gap-3"><span dir="ltr">+20 120 495 750</span><i
                            class="fa-solid fa-phone"></i></li>
                </ul>
            </div>
            <div></div>
        </div>
        <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            <p>© 2025 سافر. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const hotelId = this.dataset.hotelId;
                const icon = this.querySelector('i');
                @auth
                    fetch("{{ route('web.favorites.toggle') }}", {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                        body: JSON.stringify({ hotel_id: hotelId })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') icon.classList.add('text-red-500');
                            else icon.classList.remove('text-red-500');
                        });
                @else
                    window.location.href = "{{ route('login') }}";
                @endauth
            });
        });
    </script>
</body>

</html>