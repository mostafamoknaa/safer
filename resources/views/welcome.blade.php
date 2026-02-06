<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | رحلتك المثالية تبدأ من هنا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


    @include('partials.navbar-transparent')


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
                        @foreach($services as $service)
                            <option value="{{ $service->name_ar }}">{{ $service->name_ar }}</option>
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
                        <select name="country" class="search-select">
                            <option value="">الدولة</option>
                            @foreach($countries as $country)
                                <option value="{{ $country }}">{{ $country }}</option>
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
            <a href="{{ route('web.hotels.index') }}" class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($popularHotels as $hotel)
                <div class="hotel-card border border-gray-100 rounded-3xl overflow-hidden bg-white block relative">
                    <a href="{{ route('web.hotels.show', $hotel->id) }}">
                        <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600' }}"
                            alt="{{ $hotel->name_ar }}" class="w-full h-56 object-cover">
                    </a>
                    <button
                        class="favorite-btn absolute top-4 right-4 w-10 h-10 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition shadow-sm z-10"
                        data-hotel-id="{{ $hotel->id }}">
                        <i
                            class="fa-solid fa-heart {{ auth()->check() && auth()->user()->favorites()->where('favoritable_id', $hotel->id)->exists() ? 'text-red-500' : '' }}"></i>
                    </button>
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
                </div>
            @endforeach
        </div>
    </section>

    <!-- Nearby Places -->
    <section class="py-16 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">الاماكن القريبه</h2>
            <a href="{{ route('web.hotels.nearby') }}" id="nearby-view-all"
                class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div id="nearby-hotels-container" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Loading State -->
            <div
                class="col-span-full text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                <div
                    class="animate-spin inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full mb-4">
                </div>
                <p class="text-gray-500 font-bold">جاري البحث عن فنادق قريبة منك...</p>
            </div>
        </div>
    </section>

    <!-- Discover Places -->
    <section class="py-16 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-8">
            <h2 class="text-3xl font-bold">اكتشاف اماكن</h2>
            <a href="{{ route('web.hotels.discovery') }}" class="text-safer-blue font-semibold text-sm">عرض الكل</a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @foreach($events as $event)
                <div
                    class="relative h-[480px] rounded-[3rem] overflow-hidden shadow-2xl group transition-all duration-500 hover:-translate-y-2">
                    @php
                        $images = $event->activity_images;
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                        $imageUrl = $firstImage ? asset('storage/' . $firstImage) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $event->name_ar }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/20 to-transparent flex flex-col justify-end p-10 text-white text-right">
                        <h3 class="text-3xl font-black mb-1 drop-shadow-lg">{{ $event->name_ar }}</h3>
                        <p class="text-lg opacity-85 font-medium">{{ $event->description_ar ?: 'اكتشف روعة المكان' }}</p>
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

    <!-- Download App Section -->
    <section class="py-20 px-8">
        <div
            class="max-w-7xl mx-auto rounded-[40px] bg-gradient-to-r from-blue-600 to-blue-500 overflow-hidden relative min-h-[400px] flex flex-col items-center justify-center text-center text-white">
            <div class="relative z-10 px-4">
                <h2 class="text-4xl md:text-5xl font-bold mb-6">حمّل التطبيق وانضم كمضيف</h2>
                <p class="text-lg md:text-xl opacity-90 mb-10 max-w-2xl mx-auto">
                    حوّل مكانك إلى مصدر دخل انضم إلى تطبيقنا كمضيف وابدأ في استقبال الزوار وتحقيق أرباح بسهولة وأمان.
                </p>
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="#" class="transition hover:scale-105">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                            alt="App Store" class="h-12">
                    </a>
                    <a href="#" class="transition hover:scale-105">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                            alt="Google Play" class="h-12">
                    </a>
                </div>
            </div>
            <!-- Decorative background elements can be added here if needed -->
        </div>
    </section>

    <!-- Footer -->
    @include('partials.footer')


    <script>
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const hotelId = this.dataset.hotelId;
                const icon = this.querySelector('i');

                @auth
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    fetch("{{ route('web.favorites.toggle') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ hotel_id: hotelId }),
                        credentials: 'same-origin'
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.status === 'added') {
                                icon.classList.add('text-red-500');
                            } else {
                                icon.classList.remove('text-red-500');
                            }
                        })
                        .catch(err => console.error('Error:', err));
                @else
                    window.location.href = "{{ route('login') }}";
                @endauth
            });
        });

        // Geolocation-based Hotels
        if ("geolocation" in navigator) {
            navigator.geolocation.getCurrentPosition(function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                console.log('My Location:', { lat, lng });

                // Update "View All" link for nearby hotels
                const viewAllLink = document.getElementById('nearby-view-all');
                if (viewAllLink) {
                    viewAllLink.href = `{{ route('web.hotels.nearby') }}?lat=${lat}&lng=${lng}`;
                }

                fetch(`/api/hotels/nearest?lat=${lat}&lng=${lng}`)
                    .then(response => response.json())
                    .then(data => {
                        const container = document.getElementById('nearby-hotels-container');
                        if (data.success && data.data.length > 0) {
                            let html = '';

                            data.data.forEach(hotel => {
                                const imageUrl = hotel.images && hotel.images.length > 0 ? hotel.images[0].url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=500';
                                const priceText = hotel.price ? `${hotel.price.min.toLocaleString()} جنيه / ليلة` : 'اتصل لمعرفة السعر';
                                const provinceName = hotel.province ? hotel.province.name_ar : 'مصر';

                                html += `
                                    <div class="hotel-card relative border border-gray-100 rounded-3xl overflow-hidden bg-white block">
                                        <div class="relative">
                                            <a href="/hotels/${hotel.id}">
                                                <img src="${imageUrl}" alt="${hotel.name_ar}" class="w-full h-64 object-cover">
                                            </a>
                                            <button class="favorite-btn absolute top-4 right-4 w-8 h-8 bg-white/80 backdrop-blur-sm rounded-full flex items-center justify-center text-gray-400 hover:text-red-500 transition shadow-sm z-10" data-hotel-id="${hotel.id}">
                                                <i class="fa-solid fa-heart ${hotel.is_favorite ? 'text-red-500' : ''}"></i>
                                            </button>
                                            <div class="rating-badge flex items-center gap-1 text-sm font-bold">
                                                <svg class="w-3 h-3 text-safer-star fill-current" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" /></svg>
                                                ${Number(hotel.rate || 4.5).toFixed(1)}
                                            </div>
                                        </div>
                                        <div class="p-4">
                                            <h3 class="font-bold text-lg mb-2 text-gray-900">${hotel.name_ar}</h3>
                                            <div class="flex items-center gap-1 text-safer-blue text-xs mb-3">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                                ${provinceName}
                                            </div>
                                            <p class="text-safer-price font-bold text-sm">${priceText}</p>
                                        </div>
                                    </div>
                                `;
                            });

                            container.innerHTML = html;

                            // Re-bind favorite buttons
                            container.querySelectorAll('.favorite-btn').forEach(btn => {
                                btn.addEventListener('click', function (e) {
                                    e.preventDefault();
                                    const hotelId = this.dataset.hotelId;
                                    const icon = this.querySelector('i');
                                    @auth
                                        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                                        fetch("{{ route('web.favorites.toggle') }}", {
                                            method: 'POST',
                                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                                            body: JSON.stringify({ hotel_id: hotelId }),
                                            credentials: 'same-origin'
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
                        } else {
                            container.innerHTML = `
                                <div class="col-span-full text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                                    <i class="fa-solid fa-map-location-dot text-4xl text-gray-300 mb-4"></i>
                                    <h3 class="text-xl font-bold text-gray-900 mb-2">عذراً، لا توجد فنادق قريبة منك حالياً</h3>
                                    <p class="text-gray-500">جرب البحث في نطاق أوسع أو في مدينة أخرى.</p>
                                </div>
                            `;
                        }
                    });
            }, function (error) {
                document.getElementById('nearby-hotels-container').innerHTML = `
                    <div class="col-span-full text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                        <i class="fa-solid fa-location-slash text-4xl text-red-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">تعذر تحديد موقعك</h3>
                        <p class="text-gray-500">يرجى تفعيل إذن الوصول للموقع لعرض الفنادق القريبة منك.</p>
                    </div>
                `;
            });
        }
    </script>
</body>

</html>