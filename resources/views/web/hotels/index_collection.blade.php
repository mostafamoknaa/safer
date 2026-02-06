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
    @include('partials.navbar')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Automatic Geolocation Fetching if not present
        window.onload = function () {
            const urlParams = new URLSearchParams(window.location.search);
            if (!urlParams.has('lat') || !urlParams.has('lng')) {
                if ("geolocation" in navigator) {
                    navigator.geolocation.getCurrentPosition(function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        console.log('My Location:', { lat, lng });
                        window.location.href = `{{ route('web.hotels.nearby') }}?lat=${lat}&lng=${lng}`;
                    });
                }
            }
        }
    </script>

    <!-- Content -->
    <main class="py-12 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-gray-900 border-r-4 border-blue-600 pr-4">{{ $title }}</h1>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 relative min-h-[400px]">
            <!-- Loading Overlay -->
            <div id="loading-overlay"
                class="absolute inset-0 z-50 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center rounded-[40px] {{ request()->filled('lat') ? 'hidden' : '' }}">
                <div
                    class="animate-spin inline-block w-12 h-12 border-4 border-blue-600 border-t-transparent rounded-full mb-4">
                </div>
                <p class="text-gray-600 font-bold text-lg">جاري تحديد موقعك وعرض الفنادق القريبة...</p>
            </div>

            @forelse($hotels as $hotel)
                <div
                    class="hotel-card border border-gray-100 rounded-3xl overflow-hidden bg-white block relative shadow-sm">
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
                    <div class="p-6 text-right">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="font-bold text-lg text-gray-900 leading-tight">
                                {{ $hotel->name_ar }} / {{ $hotel->province ? $hotel->province->name_ar : 'مصر' }}
                            </h3>
                        </div>
                        <div class="flex items-center justify-between mt-auto">
                            <div class="flex gap-1 text-yellow-400">
                                @php $rating = round($hotel->rate ?? $hotel->reviews_avg_rating ?? 5); @endphp
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-4 h-4 {{ $i < $rating ? 'fill-current' : 'text-gray-200' }}"
                                        viewBox="0 0 20 20">
                                        <path
                                            d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z" />
                                    </svg>
                                @endfor
                            </div>
                            <p class="text-[#FF6B6B] font-bold">
                                {{ $hotel->rooms->min('price_per_night') ? number_format($hotel->rooms->min('price_per_night')) . ' ج.م / ليلة' : 'اتصل بالسعر' }}
                            </p>
                        </div>
                    </div>
                </div>
            @empty
                @if(request()->filled('lat'))
                    <div
                        class="col-span-full text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                        <i class="fa-solid fa-map-location-dot text-4xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">عذراً، لا توجد فنادق قريبة منك حالياً</h3>
                        <p class="text-gray-500">جرب البحث في نطاق أوسع أو في مدينة أخرى.</p>
                    </div>
                @endif
            @endforelse
        </div>

        <!-- Pagination -->
        @if(is_object($hotels) && method_exists($hotels, 'links'))
            <div class="mt-16 flex justify-center">
                {{ $hotels->links() }}
            </div>
        @endif
    </main>

    @include('partials.footer')

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