<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | {{ $car->name_ar }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Cairo, sans-serif;
        }

        .gallery-main {
            height: 400px;
            border-radius: 20px;
            overflow: hidden;
        }

        .gallery-thumb {
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .gallery-thumb.active {
            border-color: #2563eb;
        }

        .feature-box {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    <nav
        class="bg-white px-8 py-4 shadow-sm flex items-center justify-between sticky top-0 z-50 border-b border-gray-100">
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
                    class="bg-blue-600 text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                    تسجيل دخول
                </a>
            @endauth
        </div>
    </nav>

    <main class="py-8 px-4 md:px-8 max-w-7xl mx-auto min-h-screen">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500 font-medium">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600">الرئيسية</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.private_cars.index') }}" class="hover:text-blue-600">السيارات الخاصة</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $car->name_ar }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Right Column: Images & Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Gallery -->
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100">
                    <div class="gallery-main mb-4 relative group">
                        @if($car->media->count() > 0)
                            <img id="mainImage" src="{{ $car->media->first()->file_url }}" alt="{{ $car->name_ar }}"
                                class="w-full h-full object-cover transition duration-500">
                        @else
                            <img id="mainImage" src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800"
                                alt="{{ $car->name_ar }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    @if($car->media->count() > 1)
                        <div class="flex gap-4 overflow-x-auto pb-2">
                            @foreach($car->media as $media)
                                <div class="gallery-thumb w-24 flex-shrink-0 cursor-pointer {{ $loop->first ? 'active' : '' }}"
                                    onclick="changeImage('{{ $media->file_url }}', this)">
                                    <img src="{{ $media->file_url }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Technical Specs -->
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 text-right">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-3xl font-black text-gray-900">{{ $car->name_ar }}</h1>
                        <div
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full font-bold text-sm border border-blue-100">
                            {{ $car->car_model }}
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-car-side text-blue-500"></i>
                        المواصفات الفنية
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                        <!-- Power -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-bolt text-2xl text-yellow-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">قوة المحرك</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->power }} HP</p>
                        </div>

                        <!-- Acceleration -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-stopwatch text-2xl text-red-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">التسارع (0-100)</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->acceleration }} s</p>
                        </div>

                        <!-- Max Speed -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gauge-high text-2xl text-blue-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">السرعة القصوى</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->max_speed }} km/h</p>
                        </div>

                        <!-- Transmission -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gears text-2xl text-gray-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">ناقل الحركة</p>
                            <p class="font-black text-gray-800">
                                {{ $car->transmission == 'automatic' ? 'أوتوماتيك' : $car->transmission }}
                            </p>
                        </div>

                        <!-- Fuel Type -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gas-pump text-2xl text-green-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">نوع الوقود</p>
                            <p class="font-black text-gray-800">
                                {{ $car->fuel_type == 'petrol' ? 'بنزين' : $car->fuel_type }}
                            </p>
                        </div>

                        <!-- Seats -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-chair text-2xl text-purple-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">عدد المقاعد</p>
                            <p class="font-black text-gray-800">{{ $car->seats_count }} مقاعد</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        تفاصيل إضافية
                    </h3>

                    <div
                        class="prose max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <div class="mb-4">
                            <strong class="block text-gray-900 mb-1">الوصف بالعربية:</strong>
                            <p>{{ $car->notes_ar ?? 'لا يوجد وصف متاح.' }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <strong class="block text-gray-900 mb-1">English Description:</strong>
                            <p class="dir-ltr text-left">{{ $car->notes_en ?? 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Column: Booking -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[32px] p-8 shadow-lg border border-gray-100 sticky top-28 text-right">
                    <div class="flex items-end justify-between mb-8 pb-8 border-b border-gray-100">
                        <div>
                            <p class="text-sm text-gray-500 mb-1 font-bold">السعر اليومي</p>
                            <p class="text-3xl font-black text-blue-600 dir-ltr tracking-tight">
                                {{ number_format($car->price_per_day) }} <span
                                    class="text-base text-gray-400 font-normal">ج.م</span>
                            </p>
                        </div>
                    </div>

                    <form action="#" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الاستلام</label>
                            <input type="date" name="pickup_date"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ التسليم</label>
                            <input type="date" name="return_date"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">موقع الاستلام</label>
                            <input type="text" name="pickup_location" placeholder="أدخل مكان الاستلام"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <button type="button" onclick="placeBooking()"
                            class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 mt-6 block text-center transform hover:-translate-y-1">
                            تأكيد الحجز
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4 font-medium">لن يتم خصم أي مبلغ حتى تأكيد الحجز
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-[#0A1124] text-white pt-20 pb-10 px-8 mt-20">
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
                <h3 class="font-bold text-lg mb-6">روابط سريعة</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li>الرئيسية</li>
                    <li>الإقامات</li>
                    <li>الخدمات</li>
                    <li>الانشطة</li>
                    <li>اتصل بنا</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">تواصل معنا</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
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
        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            <p>© 2025 سافر. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script>
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        function placeBooking() {
            Swal.fire({
                title: 'تم استلام طلبك!',
                text: 'سيتم التواصل معك قريباً لتأكيد الحجز.',
                icon: 'success',
                confirmButtonText: 'حسناً',
                confirmButtonColor: '#2563eb'
            });
        }
    </script>
</body>

</html>