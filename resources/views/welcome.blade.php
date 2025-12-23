{{-- resources/views/welcome.blade.php --}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رحلتك المثالية تبدأ من هنا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap');
        
        * {
            font-family: 'Cairo', sans-serif;
        }

        .hero-section {
            background: linear-gradient(135deg, rgba(131, 202, 235, 0.9) 0%, rgba(26, 122, 159, 0.9) 100%),
                        url('/hero.png') center/cover;
            position: relative;
            overflow: hidden;
            min-height: 500px;
        }

        .wave {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            overflow: hidden;
            line-height: 0;
        }

        .wave svg {
            position: relative;
            display: block;
            width: calc(100% + 1.3px);
            height: 100px;
        }

        .search-box {
            background: white;
            border-radius: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
        }

        .hotel-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border-radius: 16px;
            overflow: hidden;
            background: white;
        }

        .hotel-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.15);
        }

        .star-rating {
            color: #fbbf24;
        }

        .rating-badge {
            position: absolute;
            top: 16px;
            left: 16px;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 8px 12px;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 4px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .activity-card {
            position: relative;
            height: 400px;
            border-radius: 20px;
            overflow: hidden;
            cursor: pointer;
        }

        .activity-overlay {
            position: absolute;
            inset: 0;
            background: linear-gradient(to bottom, transparent 0%, rgba(0,0,0,0.7) 100%);
            display: flex;
            flex-direction: column;
            justify-content: flex-end;
            padding: 2rem;
            color: white;
        }

        .carousel-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            transition: all 0.3s;
        }

        .carousel-dot.active {
            background: #3b82f6;
            width: 24px;
            border-radius: 5px;
        }
    </style>
</head>
<body class="bg-gray-50">
    {{-- Navigation --}}
    <nav class="absolute top-0 left-0 right-0 z-50 bg-transparent">
        <div class="container mx-auto px-6 py-6">
            <div class="flex items-center justify-between">
                <button class="bg-blue-600 text-white px-8 py-3 rounded-full hover:bg-blue-700 transition font-semibold">
                    تسجيل دخول
                </button>
                <div class="flex items-center space-x-8 space-x-reverse">
                    <div class="hidden md:flex space-x-8 space-x-reverse text-white">
                        <a href="#" class="hover:text-blue-200 transition">الرئيسية</a>
                        <a href="#" class="hover:text-blue-200 transition">الاقامات</a>
                        <a href="#" class="hover:text-blue-200 transition">الخدمات</a>
                        <a href="#" class="hover:text-blue-200 transition">الانشطة</a>
                        <a href="#" class="hover:text-blue-200 transition">تواصل معنا</a>
                    </div>
                    <div class="relative">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full w-5 h-5 flex items-center justify-center">3</span>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Hero Section --}}
    <section class="hero-section py-32 relative">
        <div class="container mx-auto px-6 relative z-10">
            <div class="text-center text-white mb-16">
                <h1 class="text-5xl md:text-6xl font-bold mb-4">رحلتك المثالية تبدأ من هنا</h1>
            </div>

            {{-- Search Box --}}
            <div class="search-box max-w-3xl mx-auto p-3">
                <div class="flex items-center gap-3">
                    <button class="bg-blue-600 text-white px-10 py-4 rounded-full hover:bg-blue-700 transition font-semibold flex-shrink-0">
                        بحث
                    </button>
                    
                    <div class="flex items-center gap-4 flex-1 px-4">
                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <span class="text-sm">الخدمات والمرافق</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        <div class="w-px h-6 bg-gray-300"></div>

                        <div class="flex items-center gap-2 text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            <span class="text-sm">نطاق الأسعار</span>
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </div>

                        <div class="w-px h-6 bg-gray-300"></div>

                        <div class="flex items-center gap-2 text-gray-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span class="text-sm">الوجهة</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Carousel Dots --}}
            <div class="flex justify-center gap-2 mt-12">
                <div class="carousel-dot"></div>
                <div class="carousel-dot active"></div>
                <div class="carousel-dot"></div>
            </div>
        </div>

        {{-- Wave SVG --}}
        <div class="wave">
            <svg viewBox="0 0 1200 120" preserveAspectRatio="none">
                <path d="M0,50 Q300,100 600,50 T1200,50 L1200,120 L0,120 Z" fill="#f9fafb"></path>
            </svg>
        </div>
    </section>

    {{-- Popular Hotels Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold text-gray-800">الاماكن الرائجة</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
                    عرض الكل
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                    $hotels = [
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                        ['name' => 'فندق الماسة / القاهرة', 'price' => '١٣٠٠ جنيه / ليلة', 'rating' => 5],
                    ];
                @endphp

                @foreach($hotels as $index => $hotel)
                <div class="hotel-card shadow-md">
                    <div class="relative h-56 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600" 
                             alt="{{ $hotel['name'] }}" 
                             class="w-full h-full object-cover">
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $hotel['name'] }}</h3>
                        <div class="flex items-center justify-between mb-4">
                            <div class="star-rating flex gap-1">
                                @for($i = 0; $i < $hotel['rating']; $i++)
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                </svg>
                                @endfor
                            </div>
                            <span class="text-red-500 font-bold text-sm">{{ $hotel['price'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Nearby Hotels Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold text-gray-800">الاماكن القريبة</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
                    عرض الكل
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $nearbyHotels = [
                        ['name' => 'فندق النخيل', 'price' => '١٣٠ جنيه / ليلة', 'location' => 'دبي الامارات', 'rating' => 4.5],
                        ['name' => 'فندق النخيل', 'price' => '١٣٠ جنيه / ليلة', 'location' => 'دبي الامارات', 'rating' => 4.5],
                        ['name' => 'فندق النخيل', 'price' => '١٣٠ جنيه / ليلة', 'location' => 'دبي الامارات', 'rating' => 4.5],
                        ['name' => 'فندق النخيل', 'price' => '١٣٠ جنيه / ليلة', 'location' => 'دبي الامارات', 'rating' => 4.5],
                    ];
                @endphp

                @foreach($nearbyHotels as $hotel)
                <div class="hotel-card shadow-md">
                    <div class="relative h-52 overflow-hidden">
                        <img src="https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500" 
                             alt="{{ $hotel['name'] }}" 
                             class="w-full h-full object-cover">
                        <div class="rating-badge">
                            <svg class="w-4 h-4 text-yellow-400 fill-current" viewBox="0 0 20 20">
                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                            </svg>
                            <span class="text-gray-800 text-sm">{{ $hotel['rating'] }}</span>
                        </div>
                        <button class="absolute top-4 right-4 bg-white/90 backdrop-blur-sm rounded-full p-2 hover:bg-white transition">
                            <svg class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $hotel['name'] }}</h3>
                        <div class="flex items-center gap-2 text-blue-600 text-sm mb-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            </svg>
                            <span>{{ $hotel['location'] }}</span>
                        </div>
                        <p class="text-red-500 font-bold text-sm">{{ $hotel['price'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Activities Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6">
            <div class="flex items-center justify-between mb-10">
                <h2 class="text-3xl font-bold text-gray-800">اكتشاف اماكن</h2>
                <a href="#" class="text-blue-600 hover:text-blue-700 font-semibold flex items-center gap-2">
                    عرض الكل
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @php
                    $activities = [
                        [
                            'title' => 'شاطئ النخلة',
                            'description' => 'يجمع شاطئ النخلة والرصاص بين الاسترخاء والترفيه حيث يمكنك الاستمتاع برياضة مائية مثيرة',
                            'image' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=600'
                        ],
                        [
                            'title' => 'شاطئ النخلة',
                            'description' => 'يجمع شاطئ النخلة والرصاص بين الاسترخاء والترفيه حيث يمكنك الاستمتاع برياضة مائية مثيرة',
                            'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=600'
                        ],
                        [
                            'title' => 'شاطئ النخلة',
                            'description' => 'يجمع شاطئ النخلة والرصاص بين الاسترخاء والترفيه حيث يمكنك الاستمتاع برياضة مائية مثيرة',
                            'image' => 'https://images.unsplash.com/photo-1559827260-dc66d52bef19?w=600'
                        ],
                    ];
                @endphp

                @foreach($activities as $activity)
                <div class="activity-card">
                    <img src="{{ $activity['image'] }}" alt="{{ $activity['title'] }}" class="w-full h-full object-cover">
                    <div class="activity-overlay">
                        <h3 class="text-2xl font-bold mb-3">{{ $activity['title'] }}</h3>
                        <p class="text-white/90 text-sm leading-relaxed">{{ $activity['description'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Carousel Dots --}}
            <div class="flex justify-center gap-2 mt-8">
                <div class="carousel-dot active"></div>
                <div class="carousel-dot"></div>
                <div class="carousel-dot"></div>
            </div>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-gray-900 text-white py-12">
        <div class="container mx-auto px-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                <div>
                    <h3 class="text-2xl font-bold mb-4">سافر</h3>
                    <p class="text-gray-400 text-sm leading-relaxed">منصتك المثالية لحجز الفنادق والرحلات السياحية في جميع أنحاء العالم</p>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-lg">روابط سريعة</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition">عن الموقع</a></li>
                        <li><a href="#" class="hover:text-white transition">الخدمات</a></li>
                        <li><a href="#" class="hover:text-white transition">الشروط والأحكام</a></li>
                        <li><a href="#" class="hover:text-white transition">سياسة الخصوصية</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-lg">الدعم</h4>
                    <ul class="space-y-3 text-gray-400 text-sm">
                        <li><a href="#" class="hover:text-white transition">مركز المساعدة</a></li>
                        <li><a href="#" class="hover:text-white transition">تواصل معنا</a></li>
                        <li><a href="#" class="hover:text-white transition">الأسئلة الشائعة</a></li>
                        <li><a href="#" class="hover:text-white transition">البلاغات</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="font-semibold mb-4 text-lg">تابعنا</h4>
                    <div class="flex gap-4">
                        <a href="#" class="bg-gray-800 hover:bg-blue-600 transition w-10 h-10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-blue-400 transition w-10 h-10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                        </a>
                        <a href="#" class="bg-gray-800 hover:bg-pink-600 transition w-10 h-10 rounded-full flex items-center justify-center">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>