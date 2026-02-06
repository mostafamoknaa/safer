<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | المفضلة</title>
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
            border-radius: 24px;
            overflow: hidden;
        }
    </style>
</head>

<body class="bg-white">
    <!-- Navbar -->
    @include('partials.navbar')
    <!-- Content -->
    <main class="py-12 px-8 max-w-7xl mx-auto min-h-screen">
        <h1 class="text-4xl font-bold text-gray-900 mb-12 text-right">المفضلة</h1>

        @if($favorites->isEmpty())
            <div class="text-center py-20 bg-gray-50 rounded-[40px] border-2 border-dashed border-gray-200">
                <i class="fa-solid fa-heart-crack text-6xl text-gray-300 mb-4"></i>
                <p class="text-gray-500 text-xl font-semibold">لا يوجد أماكن في مفضلتك حالياً</p>
                <a href="{{ route('web.home') }}" class="inline-block mt-6 text-blue-600 font-bold hover:underline">اكتشف
                    الأماكن الرائعة</a>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($favorites as $favorite)
                    @php $hotel = $favorite->hotel; @endphp
                    @if($hotel)
                        <div class="hotel-card border border-gray-100 bg-white relative group">
                            <a href="{{ route('web.hotels.show', $hotel->id) }}">
                                <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600' }}"
                                    alt="{{ $hotel->name_ar }}" class="w-full h-72 object-cover">
                            </a>
                            <button
                                class="favorite-btn absolute top-5 left-5 w-10 h-10 bg-white/90 backdrop-blur-sm rounded-full flex items-center justify-center text-red-500 shadow-lg z-10 transition hover:scale-110"
                                data-hotel-id="{{ $hotel->id }}">
                                <i class="fa-solid fa-heart"></i>
                            </button>
                            <div class="p-6 text-right">
                                <h3 class="font-bold text-xl mb-3 text-gray-900">{{ $hotel->name_ar }} /
                                    {{ $hotel->province ? $hotel->province->name_ar : 'مصر' }}
                                </h3>
                                <div class="flex items-center justify-between">
                                    <div class="flex gap-1 text-yellow-400">
                                        @for($i = 0; $i < 5; $i++)
                                            <i
                                                class="fa-solid fa-star text-sm {{ $i < round($hotel->rate ?? 5) ? '' : 'opacity-20' }}"></i>
                                        @endfor
                                    </div>
                                    <p class="text-red-500 font-bold text-lg">
                                        {{ $hotel->rooms->min('price_per_night') ? number_format($hotel->rooms->min('price_per_night')) : '1200' }}
                                        جنيه / ليلة
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @endif
    </main>

    <!-- Footer -->
    @include('partials.footer')


    <script>
        document.querySelectorAll('.favorite-btn').forEach(btn => {
            btn.addEventListener('click', function (e) {
                e.preventDefault();
                const card = this.closest('.hotel-card');
                const hotelId = this.dataset.hotelId;

                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                fetch("{{ route('web.favorites.toggle') }}", {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                    body: JSON.stringify({ hotel_id: hotelId })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'removed') {
                            card.style.opacity = '0';
                            setTimeout(() => { card.remove(); if (document.querySelectorAll('.hotel-card').length === 0) location.reload(); }, 300);
                        }
                    });
            });
        });
    </script>
</body>

</html>