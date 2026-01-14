<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | الأنشطة والرحلات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
        }

        .text-safer-blue {
            color: #2C67FF;
        }

        .bg-safer-blue {
            background-color: #2C67FF;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .event-card {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.navbar')

    <div class="container mx-auto px-4 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-bold text-gray-900 mb-2 border-r-4 border-blue-600 pr-4">الفعاليات والأنشطة</h1>
            <p class="text-gray-600 pr-6">استمتع بأفضل الرحلات والأنشطة السياحية</p>
        </div>

        @if($events->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach($events as $event)
                    <div
                        class="event-card group relative bg-white rounded-[24px] overflow-hidden border border-gray-100 h-full flex flex-col">
                        <!-- Image Container -->
                        <div class="relative h-64 overflow-hidden">
                            <img src="https://images.unsplash.com/photo-1570125909232-eb2be3b3806f?w=800"
                                alt="{{ $event->arrival_location_ar }}"
                                class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                            <!-- Overlay Gradient -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-90"></div>

                            <!-- Date Badge -->
                            <div
                                class="absolute top-4 right-4 bg-safer-blue text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
                                {{ \Carbon\Carbon::parse($event->trip_date)->format('d M') }}
                            </div>

                            <!-- Favorite Button -->
                            <button
                                class="absolute top-4 left-4 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-red-500 hover:text-white transition duration-300">
                                <i class="fa-regular fa-heart"></i>
                            </button>
                        </div>

                        <!-- Content -->
                        <div class="p-6 flex-1 flex flex-col text-right">
                            <div class="flex items-center gap-2 text-gray-500 text-xs mb-3">
                                <i class="fa-solid fa-location-arrow text-blue-500"></i>
                                <span>من: {{ $event->departure_location_ar }}</span>
                            </div>

                            <h3 class="text-xl font-bold text-gray-900 mb-3 line-clamp-1 group-hover:text-blue-600 transition">
                                رحلة إلى {{ $event->arrival_location_ar }}
                            </h3>

                            <p class="text-gray-500 text-sm leading-relaxed mb-6 line-clamp-2">
                                استمتع برحلة مريحة ومميزة إلى {{ $event->arrival_location_ar }}. انطلاق في
                                {{ \Carbon\Carbon::parse($event->trip_time)->format('h:i A') }} لمدة {{ $event->duration_minutes }}
                                دقيقة.
                            </p>

                            <div class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4">
                                <div>
                                    <span
                                        class="text-2xl font-bold text-safer-blue">{{ number_format($event->price) }}</span>
                                    <span class="text-xs text-gray-500">ج.م / فرد</span>
                                </div>
                                <a href="{{ route('web.services.trips.show', $event->id) }}"
                                    class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:bg-safer-blue hover:text-white transition">
                                    حجز الآن
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-16 flex justify-center">
                {{ $events->links() }}
            </div>
        @else
            <div class="text-center py-20 bg-white rounded-[24px] shadow-lg">
                <div class="inline-block p-6 rounded-full bg-blue-50 mb-4">
                    <i class="fa-regular fa-calendar-xmark text-4xl text-blue-500"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">لا توجد فعاليات حالياً</h3>
                <p class="text-gray-500 mb-6">تابعنا قريباً للإعلان عن رحلات وفعاليات جديدة</p>
                <a href="{{ route('web.home') }}" 
                    class="inline-block bg-safer-blue text-white px-8 py-3 rounded-full hover:bg-blue-700 transition font-bold">
                    العودة للرئيسية
                </a>
            </div>
        @endif
    </div>

    @include('partials.footer')

</body>

</html>