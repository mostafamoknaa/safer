<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | الفنادق</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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


    <!-- Content -->
    <main class="py-12 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-gray-900 border-r-4 border-blue-600 pr-4">الفنادق المتاحة</h1>

            <!-- Search Near Me Button -->
            <button onclick="searchNearMe()"
                class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-blue-50 hover:border-blue-200 transition text-gray-600 group">
                <i class="fa-solid fa-location-crosshairs group-hover:text-blue-600"></i>
                <span class="font-bold group-hover:text-blue-600">البحث بالقرب مني</span>
            </button>
        </div>

        <script>
            function searchNearMe() {
                if ("geolocation" in navigator) {
                    Swal.fire({
                        title: 'جاري تحديد موقعك...',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    navigator.geolocation.getCurrentPosition(function (position) {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        console.log('My Location:', { lat, lng });
                        window.location.href = `{{ route('web.hotels.index') }}?lat=${lat}&lng=${lng}`;
                    }, function (error) {
                        Swal.fire({
                            title: 'خطأ',
                            text: 'لم نتمكن من الوصول إلى موقعك الجغرافي. يرجى التأكد من تفعيل الإذن.',
                            icon: 'error',
                            confirmButtonText: 'حسناً'
                        });
                    });
                } else {
                    Swal.fire({
                        title: 'عذراً',
                        text: 'متصفحك لا يدعم خاصية تحديد الموقع.',
                        icon: 'error',
                        confirmButtonText: 'حسناً'
                    });
                }
            }
        </script>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($hotels as $hotel)
                <div
                    class="hotel-card border border-gray-100 rounded-3xl overflow-hidden bg-white block relative shadow-sm">
                    <a href="{{ route('web.hotels.show', $hotel->id) }}">
                        <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=600' }}"
                            alt="{{ $hotel->name_ar }}" class="w-full h-56 object-cover">
                    </a>
                    
                    <div class="p-6">
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
                <div class="col-span-full text-center py-20">
                    <div class="inline-block p-6 rounded-full bg-blue-50 mb-4">
                        <i class="fa-solid fa-hotel text-4xl text-blue-500"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد فنادق متاحة حالياً</h3>
                    <p class="text-gray-500">نأسف، لم نتمكن من العثور على أي فنادق تطابق معايير البحث الخاصة بك.</p>
                </div>
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
</body>

</html>