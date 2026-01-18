<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | السيارات الخاصة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
        }
    </style>
</head>

<body class="bg-white">
    @include('partials.navbar')

    <!-- Content -->
    <main class="py-12 px-4 md:px-8 max-w-7xl mx-auto min-h-screen">
        <h1 class="text-4xl font-extrabold text-gray-900 mb-12 text-right">السيارات الخاصة</h1>

        <div class="grid grid-cols-1 gap-6">
            @foreach($cars as $car)
                <div
                    class="bg-white rounded-[20px] shadow-[0_2px_15px_-3px_rgba(0,0,0,0.07),0_10px_20px_-2px_rgba(0,0,0,0.04)] overflow-hidden flex flex-col md:flex-row h-auto md:h-64 border border-gray-100/50 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">

                    <!-- Image Side (Right) -->
                    <div class="w-full md:w-[320px] h-48 md:h-full relative overflow-hidden order-1 md:order-2">
                        <img src="{{ $car->media->first() ? $car->media->first()->file_url : 'https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800' }}"
                            alt="{{ $car->name_ar }}" class="w-full h-full object-cover">
                    </div>

                    <!-- Details Side (Left) -->
                    <div class="flex-1 p-6 flex flex-col justify-between order-2 md:order-1 text-right">
                        <div>
                            <!-- Rating -->
                            <div class="flex items-center justify-end gap-2 mb-2">
                                <span class="text-gray-400 text-xs font-medium">(48 تعليق)</span>
                                <div class="flex gap-0.5 text-xs text-orange-400">
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                    <i class="fa-solid fa-star"></i>
                                </div>
                            </div>

                            <!-- Title -->
                            <h2 class="text-2xl font-black text-gray-800 mb-6">{{ $car->name_ar }}</h2>
                        </div>

                        <!-- Bottom Section -->
                        <div class="flex items-end justify-between">
                            <!-- Left: Price & Buttons -->
                            <div class="flex flex-col gap-3 items-start">
                                <p class="text-blue-600 font-bold text-xl dir-ltr tracking-tight">
                                    {{ number_format($car->price_per_day) }} جنيه / اليوم
                                </p>
                                <div class="flex gap-3">
                                    <a href="{{ route('web.private_cars.show', $car) }}"
                                        class="px-8 py-2 rounded-full border border-gray-300 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                                        التفاصيل
                                    </a>
                                    <button
                                        class="px-8 py-2 rounded-full bg-blue-600 text-white text-sm font-bold hover:bg-blue-700 transition shadow-md shadow-blue-200/50">
                                        احجز الان
                                    </button>
                                </div>
                            </div>

                            <!-- Right: Specs (Year, Fuel, Transmission) -->
                            <div class="flex gap-3 text-xs text-gray-500 font-bold">
                                @if($car->transmission)
                                    <span>{{ $car->transmission == 'automatic' ? 'أوتوماتيك' : $car->transmission }}</span>
                                    <span class="text-gray-300">|</span>
                                @endif
                                @if($car->fuel_type)
                                    <span>{{ $car->fuel_type == 'petrol' ? 'بنزين' : $car->fuel_type }}</span>
                                    <span class="text-gray-300">|</span>
                                @endif
                                <span>{{ $car->car_model }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-16 flex justify-center">
            {{ $cars->links() }}
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')

</body>

</html>