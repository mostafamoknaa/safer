<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $hotel->name_ar }} | سافر</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #F9FAFB;
        }

        .text-safer-blue { color: #2C67FF; }
        .bg-safer-blue { background-color: #2C67FF; }
        .text-safer-star { color: #FFE071; }
        .text-safer-price { color: #FF6B6B; }

        .booking-card {
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
            border: 1px solid #F3F4F6;
        }

        .gallery-main {
            height: 500px;
            border-radius: 20px;
        }

        .gallery-thumb-slider {
            margin-top: 10px;
        }

        .gallery-thumb {
            height: 80px;
            border-radius: 10px;
            cursor: pointer;
            opacity: 0.6;
            transition: all 0.3s;
        }

        .swiper-slide-thumb-active .gallery-thumb {
            opacity: 1;
            border: 2px solid #2C67FF;
            transform: scale(1.05);
        }

        .amenity-card {
            background: #F3F4F6;
            transition: all 0.3s;
        }

        .amenity-card:hover {
            background: #E5E7EB;
        }

        input, select {
            background-color: #F3F4F6 !important;
            border: none !important;
            border-radius: 10px !important;
            padding: 12px !important;
        }

        @media (max-width: 768px) {
            .gallery-main { height: 300px; }
            .sidebar { order: -1; margin-bottom: 2rem; }
        }

        .swiper-button-next, .swiper-button-prev {
            color: #fff;
            background: rgba(0,0,0,0.3);
            width: 40px;
            height: 40px;
            border-radius: 50%;
            after: { font-size: 18px; }
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="bg-white px-8 py-4 shadow-sm flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <a href="{{ route('web.home') }}" class="w-10 h-10 flex items-center justify-center rounded-lg">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo" class="w-full h-full object-contain">
            </a>
        </div>
        <div class="hidden lg:flex items-center gap-8 text-gray-600 font-semibold">
            <a href="{{ route('web.home') }}" class="hover:text-safer-blue transition">الرئيسية</a>
            <a href="{{ route('web.hotels.index') }}" class="hover:text-safer-blue transition">الإقامات</a>
            <!-- Services Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-safer-blue transition">
                    <span>الخدمات</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div class="absolute top-full right-0 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 text-right">
                    <a href="{{ route('web.hotels.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-safer-blue transition rounded-t-xl">
                        حجز فنادق
                    </a>
                    <a href="{{ route('web.private_cars.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-safer-blue transition rounded-b-xl">
                        حجز سيارات خاصة
                    </a>
                </div>
            </div>
            <a href="{{ route('web.events.index') }}" class="hover:text-safer-blue transition">الأنشطة</a>
            <a href="{{ route('web.contact') }}" class="hover:text-blue-600 transition">تواصل معنا</a>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <div class="relative group">
                    <button class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100 hover:bg-gray-100 transition">
                        <span class="text-gray-900 font-bold">{{ auth()->user()->name }}</span>
                        <div class="w-10 h-10 rounded-full bg-safer-blue flex items-center justify-center text-white overflow-hidden shadow-lg border-2 border-white">
                            @if(auth()->user()->image)
                                <img src="{{ auth()->user()->image }}" alt="User" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                    </button>
                    <!-- Dropdown Menu -->
                    <div class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-3 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[100] text-right">
                        <a href="{{ route('web.profile.edit') }}" class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                            <span>الملف الشخصي</span>
                            <i class="fa-solid fa-user-pen text-sm"></i>
                        </a>
                        <a href="{{ route('web.favorites.index') }}" class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-safer-blue transition">
                            <span>المفضلة</span>
                            <i class="fa-solid fa-heart text-sm"></i>
                        </a>
                        <a href="{{ route('web.bookings.index') }}" class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-safer-blue transition border-b border-gray-50">
                            <span>حجوزاتي</span>
                            <i class="fa-solid fa-calendar-check text-sm"></i>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit" class="w-full flex items-center justify-end gap-3 px-6 py-3 text-red-500 hover:bg-red-50 transition">
                                <span>تسجيل خروج</span>
                                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}" class="bg-safer-blue text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                    تسجيل دخول
                </a>
            @endauth
        </div>
    </nav>

    <main class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="mb-8 flex flex-col items-center text-center">
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ $hotel->name_ar }}</h1>
            <div class="flex flex-wrap items-center justify-center gap-4 text-gray-500">
                <div class="flex items-center gap-1">
                    <i class="fa-solid fa-location-dot text-safer-blue"></i>
                    <span>{{ $hotel->province->name_ar ?? 'الجيزة' }} - مصر</span>
                </div>
                <div class="flex items-center gap-1">
                    <button class="favorite-btn flex items-center gap-2 px-4 py-1.5 rounded-full border border-gray-100 hover:bg-gray-50 transition" data-hotel-id="{{ $hotel->id }}">
                        <i class="fa-solid fa-heart {{ auth()->check() && auth()->user()->favorites()->where('favoritable_id', $hotel->id)->exists() ? 'text-red-500' : 'text-gray-300' }}"></i>
                        <span class="text-sm font-bold text-gray-600">حفظ ف المفضلة</span>
                    </button>
                </div>
                <div class="flex items-center gap-1 text-safer-star">
                    @php $rating = round($hotel->rate ?? 5); @endphp
                    @for($i = 0; $i < 5; $i++)
                        <i class="fa-solid fa-star {{ $i < $rating ? '' : 'text-gray-200' }}"></i>
                    @endfor
                    <span class="text-gray-400 text-sm mr-2">({{ $hotel->reviews->count() ?: 340 }} تعليق)</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Gallery & Content -->
            <div class="lg:col-span-2">
                <!-- Gallery with Swiper -->
                <div class="mb-12">
                    <div class="swiper gallery-main overflow-hidden border-2 border-transparent">
                        <div class="swiper-wrapper">
                            @forelse($hotel->media as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->file_url }}" class="w-full h-full object-cover" alt="{{ $hotel->name_ar }}">
                                </div>
                            @empty
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080" class="w-full h-full object-cover"></div>
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2080" class="w-full h-full object-cover"></div>
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=2080" class="w-full h-full object-cover"></div>
                            @endforelse
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                    <div thumbsSlider="" class="swiper gallery-thumb-slider">
                        <div class="swiper-wrapper">
                            @forelse($hotel->media as $media)
                                <div class="swiper-slide">
                                    <img src="{{ $media->file_url }}" class="gallery-thumb w-full object-cover">
                                </div>
                            @empty
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1571896349842-33c89424de2d?q=80&w=2080" class="gallery-thumb w-full object-cover"></div>
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?q=80&w=2080" class="gallery-thumb w-full object-cover"></div>
                                <div class="swiper-slide"><img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?q=80&w=2080" class="gallery-thumb w-full object-cover"></div>
                            @endforelse
                        </div>
                    </div>
                </div>

                <!-- Amenities -->
                <div class="mb-12 grid grid-cols-2 md:grid-cols-3 gap-4">
                    @php
                        $amenityIcons = [
                            'wifi' => ['icon' => 'fa-wifi', 'label' => 'واي فاي'],
                            'food' => ['icon' => 'fa-utensils', 'label' => 'المطعم'],
                            'parking' => ['icon' => 'fa-car', 'label' => 'المواقف'],
                            'pool' => ['icon' => 'fa-person-swimming', 'label' => 'الاسترخاء'],
                            'sports_center' => ['icon' => 'fa-dumbbell', 'label' => 'نادي رياضي'],
                            'elevator' => ['icon' => 'fa-elevator', 'label' => 'المصعد'],
                            'reception' => ['icon' => 'fa-user-tie', 'label' => 'الاستقبال'],
                            'cleaning' => ['icon' => 'fa-broom', 'label' => 'خدمة الغرف']
                        ];
                    @endphp
                    
                    @foreach($hotel->services ?? [] as $service)
                        @php $info = $amenityIcons[$service] ?? ['icon' => 'fa-check', 'label' => $service]; @endphp
                        <div class="amenity-card p-4 rounded-2xl flex items-center justify-between">
                            <i class="fa-solid {{ $info['icon'] }} text-safer-blue text-lg"></i>
                            <div class="text-right">
                                <h4 class="font-bold text-gray-800">{{ $info['label'] }}</h4>
                                <span class="text-xs text-gray-500">متاح وبجودة عالية</span>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Description -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4">وصف المكان</h2>
                    <p class="text-gray-600 leading-relaxed text-lg text-right">
                        {{ $hotel->about_info_ar }}
                    </p>
                </div>

                <!-- Available Rooms Section -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-6 text-right">الغرف المتاحة</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @forelse($hotel->rooms as $room)
                            <div class="bg-white rounded-3xl overflow-hidden border border-gray-100 shadow-sm hover:shadow-md transition group">
                                <div class="relative h-48 overflow-hidden">
                                    <img src="{{ $room->media->first() ? $room->media->first()->file_url : 'https://images.unsplash.com/photo-1590490360182-c33d57733427?q=80&w=2074' }}" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition duration-500" alt="{{ $room->name }}">
                                    <div class="absolute top-4 left-4 bg-white/90 backdrop-blur-sm px-3 py-1 rounded-xl text-safer-blue font-bold shadow-sm">
                                        {{ number_format($room->price_per_night) }} ج.م / ليلة
                                    </div>
                                </div>
                                <div class="p-6 text-right">
                                    <h3 class="font-bold text-xl mb-3 text-gray-900">{{ $room->name ?? 'غرفة فندقية فاخرة' }}</h3>
                                    <div class="flex items-center justify-end gap-4 text-gray-500 text-sm mb-4">
                                        <div class="flex items-center gap-2">
                                            <span>{{ $room->bathrooms_count }} حمام</span>
                                            <i class="fa-solid fa-bath"></i>
                                        </div>
                                        <div class="flex items-center gap-2 border-r pr-4">
                                            <span>{{ $room->beds_count }} سرير</span>
                                            <i class="fa-solid fa-bed"></i>
                                        </div>
                                        <div class="flex items-center gap-2 border-r pr-4">
                                            <span>{{ $room->rooms_count }} غرفة</span>
                                            <i class="fa-solid fa-door-open"></i>
                                        </div>
                                    </div>
                                    <button onclick="document.getElementById('roomSelector').value = '{{ $room->id }}'; calculatePrice(); document.getElementById('bookingForm').scrollIntoView({behavior: 'smooth'})" 
                                            class="w-full py-3 rounded-2xl bg-gray-50 text-gray-700 font-bold hover:bg-safer-blue hover:text-white transition group-hover:bg-safer-blue group-hover:text-white">
                                        اختيار هذه الغرفة
                                    </button>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-full p-8 text-center bg-gray-50 rounded-3xl border-2 border-dashed border-gray-200">
                                <i class="fa-solid fa-bed text-4xl text-gray-300 mb-4"></i>
                                <p class="text-gray-500">لا توجد غرف متاحة حالياً للعرض.</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Location Map -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-4 text-right">الموقع</h2>
                    <div class="rounded-3xl overflow-hidden h-[400px] border">
                        @php
                            $lat = $hotel->lat ?? '31.00';
                            $lang = $hotel->lang ?? '30.80';
                        @endphp
                        <iframe class="w-full h-full" src="https://maps.google.com/maps?q={{ $lat }},{{ $lang }}&hl=ar&z=15&t=&ie=UTF8&iwloc=B&output=embed"></iframe>
                    </div>
                </div>

                <!-- Reviews Section (Now Dynamic) -->
                <div class="mb-12">
                    <h2 class="text-2xl font-bold mb-8 text-right">تقييمات النزلاء</h2>
                    <div class="space-y-6">
                        @forelse($hotel->reviews as $review)
                        <div class="flex items-start justify-between border-b pb-6 last:border-0">
                            <div class="text-safer-star flex gap-1">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="fa-solid fa-star {{ $i <= $review->rating ? '' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <div class="flex items-center gap-4 text-right">
                                <div>
                                    <h4 class="font-bold text-gray-900">{{ $review->user->name ?? 'مستخدم غير معروف' }}</h4>
                                    <p class="text-gray-500 text-sm">{{ $review->comment }}</p>
                                    <span class="text-xs text-gray-400">{{ $review->created_at->diffForHumans() }}</span>
                                </div>
                                @if($review->user && $review->user->image)
                                    <img src="{{ Storage::url($review->user->image) }}" class="w-12 h-12 rounded-full object-cover border-2 border-white shadow-sm">
                                @else
                                    <img src="https://i.pravatar.cc/150?u={{ $review->id }}" class="w-12 h-12 rounded-full border-2 border-white shadow-sm">
                                @endif
                            </div>
                        </div>
                        @empty
                            <!-- Fallback Mock Reviews if database is empty -->
                            @foreach([
                                ['name' => 'سارة محمود', 'tag' => 'مستخدم موثق'],
                                ['name' => 'محمد أحمد', 'tag' => 'مسافر فردي'],
                                ['name' => 'ليلى علي', 'tag' => 'أسرة']
                            ] as $i => $mock)
                            <div class="flex items-start justify-between">
                                <div class="text-safer-star flex gap-1">
                                    <i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star {{ $i == 2 ? 'text-gray-200' : '' }}"></i>
                                </div>
                                <div class="flex items-center gap-4 text-right">
                                    <div>
                                        <h4 class="font-bold text-gray-900">{{ $mock['name'] }}</h4>
                                        <p class="text-gray-500 text-sm">إقامة ممتازة والمكان نظيف جداً، طاقم العمل متعاون والخدمة سريعة.</p>
                                    </div>
                                    <img src="https://i.pravatar.cc/150?u={{ $i+10 }}" class="w-12 h-12 rounded-full border-2 border-white shadow-sm">
                                </div>
                            </div>
                            @endforeach
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Sidebar: Booking -->
            <div class="lg:col-span-1">
                <div class="booking-card bg-white p-6 rounded-[2.5rem] sticky top-28">
                    <h3 class="text-2xl font-bold text-gray-900 mb-8 text-right">الحجز</h3>
                    
                    <form id="bookingForm" action="{{ route('web.bookings.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="hotel_id" value="{{ $hotel->id }}">
                        
                        <div class="space-y-2 text-right">
                            <label class="block font-bold text-gray-700">وقت الوصول</label>
                            <div class="relative">
                                <input type="date" id="checkIn" name="check_in_date" class="w-full text-right pr-10" value="{{ date('Y-m-d') }}" min="{{ date('Y-m-d') }}">
                                <i class="fa-regular fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="space-y-2 text-right">
                            <label class="block font-bold text-gray-700">وقت المغادرة</label>
                            <div class="relative">
                                <input type="date" id="checkOut" name="check_out_date" class="w-full text-right pr-10" value="{{ date('Y-m-d', strtotime('+3 days')) }}" min="{{ date('Y-m-d') }}">
                                <i class="fa-regular fa-calendar-days absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="space-y-2 text-right">
                            <label class="block font-bold text-gray-700">عدد الضيوف</label>
                            <div class="relative">
                                <select id="guestsCount" name="number_of_guests" class="w-full text-right appearance-none pr-10">
                                    <option value="1">1 شخص</option>
                                    <option value="2">2 شخص</option>
                                    <option value="3">3 شخص</option>
                                    <option value="4">4 شخص</option>
                                </select>
                                <i class="fa-solid fa-users absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="space-y-2 text-right">
                            <label class="block font-bold text-gray-700">نوع الغرفه</label>
                            <div class="relative">
                                <select id="roomSelector" name="room_id" class="w-full text-right appearance-none pr-10">
                                    @foreach($hotel->rooms as $room)
                                        <option value="{{ $room->id }}" data-price="{{ $room->price_per_night }}" data-beds="{{ $room->beds_count }}">
                                            {{ $room->name ?? 'غرفة فندقية' }} ({{ $room->rooms_count }} غرف، {{ $room->beds_count }} سرير)
                                        </option>
                                    @endforeach
                                    @if($hotel->rooms->isEmpty())
                                        <option value="0" data-price="1200" data-beds="2">الجناح الفاخر (2 سرير)</option>
                                    @endif
                                </select>
                                <i class="fa-solid fa-bed absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                            </div>
                        </div>

                        <div class="py-6 text-center">
                            <span class="text-gray-400 font-bold block mb-1">السعر الإجمالي</span>
                            <div id="totalPrice" class="text-safer-blue text-4xl font-bold">
                                0 جنية
                            </div>
                            <span id="priceDetail" class="text-xs text-gray-400 mt-2 block"></span>
                        </div>

                        <button type="submit" class="w-full bg-safer-blue text-white py-4 rounded-2xl font-bold text-lg hover:bg-opacity-90 transition shadow-lg shadow-blue-200">
                            احجز الآن
                        </button>
                        
                        <button type="button" class="w-full border border-gray-200 text-gray-500 py-3 rounded-2xl font-bold flex items-center justify-center gap-3 hover:bg-gray-50 transition">
                            <i class="fa-solid fa-comment-dots text-sm"></i>
                            تواصل مع المسئول
                        </button>
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
                <p class="text-gray-400 text-sm mb-8">موقع سياحي متكامل يساعدك على التخطيط لرحلتك بسهولة...</p>
                <div class="flex justify-end gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg" class="w-32">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" class="w-32">
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">روابط سريعة</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li>الرئيسية</li><li>الإقامات</li><li>المفضلة</li><li>حجوزاتي</li><li>تواصل معنا</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">تواصل معنا</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li class="flex items-center justify-end gap-3"><span>مصر</span><i class="fa-solid fa-location-dot"></i></li>
                    <li class="flex items-center justify-end gap-3"><span>support@alfosafr.com</span><i class="fa-solid fa-envelope"></i></li>
                    <li class="flex items-center justify-end gap-3"><span dir="ltr">+20 120 495 750</span><i class="fa-solid fa-phone"></i></li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            <p>© 2025 سافر. جميع الحقوق محفوظة.</p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // Favorite Logic
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
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
                        if (data.status === 'added') icon.classList.replace('text-gray-300', 'text-red-500');
                        else icon.classList.replace('text-red-500', 'text-gray-300');
                    });
                @else
                    window.location.href = "{{ route('login') }}";
                @endauth
            });
        });

        // Swiper initialization
        const thumbsSwiper = new Swiper(".gallery-thumb-slider", {
            spaceBetween: 10,
            slidesPerView: 4,
            freeMode: true,
            watchSlidesProgress: true,
            breakpoints: {
                640: { slidesPerView: 4 },
                1024: { slidesPerView: 6 }
            }
        });

        const mainSwiper = new Swiper(".gallery-main", {
            spaceBetween: 10,
            navigation: {
                nextEl: ".swiper-button-next",
                prevEl: ".swiper-button-prev",
            },
            thumbs: {
                swiper: thumbsSwiper,
            },
            effect: 'fade',
            fadeEffect: { crossFade: true },
        });

        // Booking logic
        const roomSelector = document.getElementById('roomSelector');
        const checkIn = document.getElementById('checkIn');
        const checkOut = document.getElementById('checkOut');
        const guestsCount = document.getElementById('guestsCount');
        const totalPriceEl = document.getElementById('totalPrice');
        const priceDetailEl = document.getElementById('priceDetail');

        function calculatePrice() {
            const pricePerNight = parseFloat(roomSelector.options[roomSelector.selectedIndex].dataset.price);
            const start = new Date(checkIn.value);
            const end = new Date(checkOut.value);
            const guests = parseInt(guestsCount.value);
            
            const nights = Math.ceil((end - start) / (1000 * 60 * 60 * 24));

            if (nights > 0) {
                const total = nights * pricePerNight * guests;
                totalPriceEl.innerText = `${total.toLocaleString()} جنيه`;
                priceDetailEl.innerText = `${nights} ليالٍ × ${guests} ضيوف`;
            } else {
                totalPriceEl.innerText = '0 جنيه';
                priceDetailEl.innerText = 'يرجى اختيار تاريخ صحيح';
            }
        }

        [roomSelector, checkIn, checkOut, guestsCount].forEach(el => {
            el.addEventListener('change', calculatePrice);
        });

        calculatePrice();

        // Handle Form Submission
        document.getElementById('bookingForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            @auth
                fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        Swal.fire({
                            title: 'تم الحجز بنجاح!',
                            text: 'سنقوم بالتواصل معك لتأكيد الحجز.',
                            icon: 'success',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#2C67FF'
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire({
                            title: 'خطأ!',
                            text: data.message || 'حدث خطأ أثناء تنفيذ الحجز.',
                            icon: 'error',
                            confirmButtonText: 'حسناً',
                            confirmButtonColor: '#2C67FF'
                        });
                    }
                })
                .catch(error => {
                    Swal.fire({
                        title: 'خطأ!',
                        text: 'حدث خطأ في الاتصال بالسيرفر.',
                        icon: 'error',
                        confirmButtonText: 'حسناً',
                        confirmButtonColor: '#2C67FF'
                    });
                });
            @else
                Swal.fire({
                    title: 'تنبيه',
                    text: 'يجب عليك تسجيل الدخول أولاً لتتمكن من الحجز.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'تسجيل دخول',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#2C67FF'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            @endauth
        });
    </script>
</body>
</html>

</html>