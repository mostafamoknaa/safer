<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | رحلتك المثالية تبدأ من هنا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
        }

        .hero-bg {
            background-image: url('{{ asset('6d48055a46c072bc627019b37459a42a8d6fa8e4 (1).png') }}');
            background-position: center;
            background-size: cover;
            height: 105vh;
        }

        .text-safer-blue {
            color: #2C67FF;
        }

        .bg-safer-blue {
            background-color: #2C67FF;
        }

        .text-safer-star {
            color: #FFE071;
        }

        .text-safer-price {
            color: #FF6B6B;
        }

        .search-box {
            background: #F3F4F6;
            border-radius: 50px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            padding: 8px;
        }

        .search-divider {
            width: 1px;
            height: 40px;
            background-color: #E5E7EB;
        }

        .search-item select {
            border: none !important;
            background: transparent !important;
            outline: none !important;
            box-shadow: none !important;
            -webkit-appearance: none;
            -moz-appearance: none;
            appearance: none;
            text-align: center;
            width: 100%;
            cursor: pointer;
            color: #4B5563;
            font-weight: 600;
        }

        .search-item select:hover {
            color: #2C67FF;
        }

        @media (max-width: 768px) {
            .search-box {
                flex-direction: column;
                border-radius: 20px;
                padding: 15px;
            }

            .search-divider {
                display: none;
            }

            .search-item {
                width: 100%;
                padding: 10px 0;
                justify-content: space-between;
                border-bottom: 1px solid #E5E7EB;
            }

            .search-item:last-child {
                border-bottom: none;
            }

            .hero-bg {
                height: auto;
                min-height: 80vh;
                padding-bottom: 40px;
            }
        }

        .hotel-card {
            transition: all 0.3s ease;
        }

        .hotel-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .rating-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            border-radius: 12px;
            padding: 4px 10px;
            color: white;
        }

        .discovery-overlay {
            background: linear-gradient(to top, rgba(0, 0, 0, 0.7), transparent);
        }

        .carousel-dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background-color: #D1D5DB;
            transition: all 0.3s ease;
        }

        .carousel-dot.active {
            background-color: #2C67FF;
            width: 12px;
        }
    </style>
</head>

<body class="bg-white">

    <!-- Navbar -->
    <nav class="absolute top-0 left-0 right-0 z-50 flex items-center justify-between px-8 py-6">

        <div class="flex items-center gap-2">
            <div class="w-10 h-10 flex items-center justify-center rounded-lg">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo"
                    class="w-full h-full object-contain">
            </div>
        </div>
        <div class="hidden lg:flex items-center gap-8 text-white">
            <a href="#"
                class="hover:opacity-80 transition text-safer-blue font-bold border-b-2 border-safer-blue">الرئيسية</a>
            <a href="#" class="hover:opacity-80 transition">الإقامات</a>
            <a href="#" class="hover:opacity-80 transition">الخدمات</a>
            <a href="#" class="hover:opacity-80 transition">الانشطة</a>
            <a href="#" class="hover:opacity-80 transition">تواصل معنا</a>
        </div>

        <div class="flex items-center gap-4">
            @auth
                <div class="flex items-center gap-4">
                    <span
                        class="text-white font-bold bg-white/20 px-4 py-2 rounded-xl backdrop-blur-sm">{{ auth()->user()->name }}</span>
                    <form action="{{ route('logout') }}" method="POST" class="m-0">
                        @csrf
                        <button type="submit"
                            class="border border-white text-white px-6 py-2 rounded-full font-semibold hover:bg-white hover:text-safer-blue transition">
                            خروج
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="bg-safer-blue text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                    تسجيل دخول
                </a>
            @endauth
        </div>

    </nav>

    <!-- Hero Section -->
    <section class=" hero-bg relative flex flex-col items-center justify-center text-center px-4">
        <h1 class="text-white text-4xl md:text-6xl font-bold z-10 mb-12">رحلتك المثالية تبدأ من هنا</h1>

        <!-- Search Bar -->
        <form action="{{ route('web.hotels.index') }}" method="GET"
            class="search-box w-full max-w-5xl z-10 flex flex-col md:flex-row items-center gap-2">
            <button type="submit"
                class="bg-safer-blue text-white px-12 py-3 rounded-full font-bold hover:bg-opacity-90 transition whitespace-nowrap order-last md:order-first w-full md:w-auto mt-4 md:mt-0">
                بحث
            </button>

            <div class="flex-1 flex flex-col md:flex-row items-center w-full">
                <!-- Services -->
                <div class="search-item flex-1 flex items-center justify-center gap-3 px-6 cursor-pointer group w-full">
                    <i
                        class="fa-solid fa-chevron-down text-gray-400 text-xs group-hover:text-safer-blue transition"></i>
                    <select name="service" class="search-select">
                        <option value="">الخدمات والمرافق</option>
                        @php
                            $serviceMap = [
                                'wifi' => 'واى فاى',
                                'parking' => 'مواقف سيارات',
                                'pool' => 'حمام سباحة',
                                'food' => 'مطعم',
                                'sports_center' => 'نادي رياضي',
                                'elevator' => 'مصعد',
                                'social_rooms' => 'قاعات اجتماعات',
                                'opening' => 'إطلالة',
                                'kitchen' => 'مطبخ',
                                'dishes_silverware' => 'أدوات مائدة',
                                'hot_water_kettle' => 'غلاية ماء',
                                'crib' => 'سرير أطفال',
                                'smoke_alarm' => 'إنذار حريق',
                                'hangers' => 'شماعات',
                                'iron' => 'مكواة'
                            ];
                        @endphp
                        @foreach($services as $service)
                            <option value="{{ $service }}">{{ $serviceMap[$service] ?? $service }}</option>
                        @endforeach
                    </select>
                    <i class="fa-solid fa-microchip text-gray-400 group-hover:text-safer-blue transition"></i>
                </div>

                <div class="search-divider"></div>

                <!-- Price Range -->
                <div class="search-item flex-1 flex items-center justify-center gap-3 px-6 cursor-pointer group w-full">
                    <i
                        class="fa-solid fa-chevron-down text-gray-400 text-xs group-hover:text-safer-blue transition"></i>
                    <select name="price_range" class="search-select">
                        <option value="">نطاق السعر</option>
                        <option value="0-500">0 - 500 ج.م</option>
                        <option value="500-1000">500 - 1000 ج.م</option>
                        <option value="1000-2000">1000 - 2000 ج.م</option>
                        <option value="2000+">أكثر من 2000 ج.م</option>
                    </select>
                    <i class="fa-solid fa-money-bill-transfer text-gray-400 group-hover:text-safer-blue transition"></i>
                </div>

                <div class="search-divider"></div>

                <!-- Country -->
                <div class="search-item flex-1 flex items-center justify-center gap-3 px-6 cursor-pointer group w-full">
                    <i
                        class="fa-solid fa-chevron-down text-gray-400 text-xs group-hover:text-safer-blue transition"></i>
                    <div class="flex-1">
                        <select name="province_id" class="search-select">
                            <option value="">الدولة</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name_ar }}</option>
                            @endforeach
                        </select>
                    </div>
                    <i class="fa-solid fa-location-dot text-gray-400 group-hover:text-safer-blue transition"></i>
                </div>
            </div>
        </form>
    </section>

    <!-- Popular Places -->
    <section class="py-16 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">الاماكن الرائجة</h2>
            <a href="#" class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularHotels as $hotel)
                <a href="{{ route('web.hotels.show', $hotel->id) }}"
                    class="hotel-card border border-gray-100 rounded-3xl overflow-hidden bg-white block">
                    <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600' }}"
                        alt="{{ $hotel->name_ar }}" class="w-full h-56 object-cover">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-2">
                            <h3 class="font-bold text-lg text-gray-900">{{ $hotel->name_ar }} /
                                {{ $hotel->province ? $hotel->province->name_ar : 'مصر' }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-between">
                            <div class="flex gap-1 text-safer-star">
                                @php $rating = round($hotel->rate ?? 5); @endphp
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < $rating ? 'fill-current' : 'text-gray-200' }}"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-safer-price font-bold">
                                {{ $hotel->rooms->min('price_per_night') ? number_format($hotel->rooms->min('price_per_night')) . ' جنيه / ليلة' : 'اتصل لمعرفة السعر' }}
                            </p>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Nearby Places -->
    <section class="py-16 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">الاماكن القريبه</h2>
            <a href="#" class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($nearbyHotels as $hotel)
                <a href="{{ route('web.hotels.show', $hotel->id) }}"
                    class="hotel-card relative border border-gray-100 rounded-3xl overflow-hidden bg-white block">
                    <div class="relative">
                        <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500' }}"
                            alt="{{ $hotel->name_ar }}" class="w-full h-64 object-cover">
                        <div class="rating-badge flex items-center gap-1 text-sm font-bold">
                            <svg class="w-3 h-3 text-safer-star fill-current" viewBox="0 0 20 20">
                                <path
                                    d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                            </svg>
                            {{ number_format($hotel->rate ?? 4.5, 1) }}
                        </div>
                    </div>
                    <div class="p-4">
                        <h3 class="font-bold text-lg mb-2 text-gray-900">{{ $hotel->name_ar }}</h3>
                        <div class="flex items-center gap-1 text-safer-blue text-xs mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                </path>
                            </svg>
                            {{ $hotel->province ? $hotel->province->name_ar : 'مصر' }}
                        </div>
                        <p class="text-safer-price font-bold text-sm">
                            {{ $hotel->rooms->min('price_per_night') ? number_format($hotel->rooms->min('price_per_night')) . ' جنيه / ليلة' : 'اتصل لمعرفة السعر' }}
                        </p>
                    </div>
                </a>
            @endforeach
        </div>
    </section>

    <!-- Discover Places -->
    <section class="py-16 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">اكتشاف اماكن</h2>
            <a href="#" class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @foreach($events as $event)
                <div class="relative h-[450px] rounded-3xl overflow-hidden shadow-lg group">
                    <img src="{{ (isset($event->image_url) && $event->image_url != 'none' && $event->image_url != '') ? $event->image_url : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800' }}"
                        alt="{{ $event->name_ar }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                    <div class="absolute inset-0 discovery-overlay flex flex-col justify-end p-8 text-white">
                        <h3 class="text-2xl font-bold mb-2">{{ $event->name_ar }}</h3>
                        <p class="text-sm opacity-90 leading-relaxed">{{ Str::limit($event->description_ar, 100) }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Carousel Indicators -->
        <div class="flex justify-center gap-2 mt-8">
            <div class="carousel-dot active"></div>
            <div class="carousel-dot"></div>
            <div class="carousel-dot"></div>
        </div>
    </section>

    <footer class="bg-gray-50 py-12 px-8 border-t border-gray-100">
        <div class="max-w-7xl mx-auto text-center">
            <h2 class="text-2xl font-bold text-safer-blue mb-4">Safer</h2>
            <p class="text-gray-500 text-sm">جميع الحقوق محفوظة &copy; {{ date('Y') }}</p>
        </div>
    </footer>

</body>

</html>