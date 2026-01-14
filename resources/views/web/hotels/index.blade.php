<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | الفنادق</title>
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


    <!-- Content -->
    <main class="py-12 px-8 max-w-7xl mx-auto">
        <div class="flex items-center justify-between mb-10">
            <h1 class="text-3xl font-bold text-gray-900 border-r-4 border-blue-600 pr-4">الفنادق المتاحة</h1>

            <!-- Filter Button (Optional/Hidden for now to match exact design, or styled minimally) -->
            <!-- <button class="flex items-center gap-2 px-6 py-3 bg-white border border-gray-200 rounded-xl hover:bg-gray-50 transition text-gray-600">
                <i class="fa-solid fa-sliders"></i>
                <span class="font-bold">تصفية النتائج</span>
            </button> -->
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
            @forelse($hotels as $hotel)
                <div class="hotel-card group relative bg-white rounded-[24px] overflow-hidden border border-gray-100">
                    <!-- Image Container -->
                    <div class="relative h-[320px] overflow-hidden">
                        <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1566073771259-6a8506099945?w=800' }}"
                            alt="{{ app()->getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en }}"
                            class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                        <!-- Overlay Gradient -->
                        <div
                            class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent opacity-90">
                        </div>

                        <!-- Top Badges -->
                        <div class="absolute top-4 left-4 right-4 flex justify-between items-start">
                            <div class="rating-badge flex items-center gap-1.5 px-3 py-1.5 rounded-full">
                                <span class="text-white font-bold text-sm">{{ number_format($hotel->rate ?? 0, 1) }}</span>
                                <i class="fa-solid fa-star text-yellow-400 text-xs"></i>
                            </div>
                            <button
                                class="w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-red-500 hover:text-white transition duration-300">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>

                        <!-- Content Overlay -->
                        <div class="absolute bottom-0 left-0 right-0 p-6 text-right">
                            <div class="flex items-center gap-2 text-gray-300 text-sm mb-2">
                                <i class="fa-solid fa-location-dot text-blue-400"></i>
                                <span
                                    class="truncate">{{ $hotel->province ? (app()->getLocale() == 'ar' ? $hotel->province->name_ar : $hotel->province->name_en) : 'موقع غير محدد' }}</span>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-3">
                                {{ app()->getLocale() == 'ar' ? $hotel->name_ar : $hotel->name_en }}
                            </h3>
                            <div class="flex items-end justify-between">
                                <div class="text-white">
                                    <span
                                        class="text-xl  text-red-600 font-sans">{{ number_format($hotel->rooms->min('price_per_night') ?? 0) }}</span>
                                    <span class="text-sm text-gray-300">ج.م / ليلة</span>
                                </div>
                                <a href="{{ route('web.hotels.show', $hotel) }}"
                                    class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white transform translate-y-2 opacity-0 group-hover:translate-y-0 group-hover:opacity-100 transition duration-300 delay-100">
                                    <i class="fa-solid fa-arrow-left"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Card Body (Only visible on larger screens/details) -->
                    <div class="p-5 border-t border-gray-50">
                        <div class="flex items-center justify-between text-sm text-gray-500 mb-4">
                            <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg">
                                <i class="fa-solid fa-bed text-blue-500"></i>
                                <span>{{ $hotel->rooms_count ?? 5 }} غرف</span>
                            </div>
                            <div class="flex items-center gap-2 bg-gray-50 px-3 py-1.5 rounded-lg">
                                <i class="fa-solid fa-wifi text-blue-500"></i>
                                <span>واي فاي مجاني</span>
                            </div>
                        </div>
                        <!-- Rating Stars -->
                        <div class="flex justify-end gap-1 text-xs text-yellow-400">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fa-solid fa-star {{ $i < round($hotel->rate ?? 5) ? '' : 'opacity-20' }}"></i>
                            @endfor
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
        <div class="mt-16 flex justify-center">
            {{ $hotels->links() }}
        </div>
    </main>


    @include('partials.footer')
</body>

</html>