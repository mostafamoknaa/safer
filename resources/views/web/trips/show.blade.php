<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | تفاصيل الرحلة</title>
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
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.navbar')

    <div class="container mx-auto px-4 py-8 md:py-12">
        <div class="max-w-6xl mx-auto">
            <div class="bg-white rounded-[24px] overflow-hidden shadow-lg border border-gray-100">
                {{-- Trip Image --}}
                <div class="relative h-64 md:h-96 overflow-hidden group">
                    <img src="https://images.unsplash.com/photo-1570125909232-eb2be3b3806f?w=800"
                        alt="{{ $trip->arrival_location_ar }}"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">

                    <!-- Overlay Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-90">
                    </div>

                    {{-- Date Badge --}}
                    <div
                        class="absolute top-6 right-6 bg-safer-blue text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                        {{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }}
                    </div>

                    <!-- Back Button -->
                    <a href="{{ route('web.services.buses') }}"
                        class="absolute top-6 left-6 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-safer-blue transition duration-300">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                {{-- Trip Details --}}
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Main Content --}}
                        <div class="lg:col-span-2">
                            <h1
                                class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 border-r-4 border-safer-blue pr-4">
                                رحلة إلى {{ $trip->arrival_location_ar }}
                            </h1>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
                                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div
                                        class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-safer-blue">
                                        <i class="fa-solid fa-location-arrow text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">من</p>
                                        <p class="font-bold text-gray-900">{{ $trip->departure_location_ar }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div
                                        class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                                        <i class="fa-solid fa-location-dot text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">إلى</p>
                                        <p class="font-bold text-gray-900">{{ $trip->arrival_location_ar }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div
                                        class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center text-purple-600">
                                        <i class="fa-solid fa-clock text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">وقت الانطلاق</p>
                                        <p class="font-bold text-gray-900">
                                            {{ \Carbon\Carbon::parse($trip->trip_time)->format('h:i A') }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
                                    <div
                                        class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center text-orange-600">
                                        <i class="fa-solid fa-hourglass-half text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-gray-500 text-sm">المدة</p>
                                        <p class="font-bold text-gray-900">{{ $trip->duration_minutes }} دقيقة</p>
                                    </div>
                                </div>
                            </div>

                            @if($trip->bus)
                                <div class="mb-8">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4 border-r-4 border-safer-blue pr-3">
                                        معلومات الحافلة</h3>
                                    <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100">
                                        <div class="grid grid-cols-2 md:grid-cols-3 gap-6">
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">نوع الحافلة</p>
                                                <div class="flex items-center gap-2">
                                                    <i class="fa-solid fa-bus text-safer-blue"></i>
                                                    <p class="font-bold text-gray-900">{{ $trip->bus->bus_type_ar }}</p>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">عدد المقاعد الكلي</p>
                                                <p class="font-bold text-gray-900">{{ $trip->bus->total_seats }} مقعد</p>
                                            </div>
                                            <div>
                                                <p class="text-gray-500 text-sm mb-1">المقاعد المتاحة</p>
                                                <p
                                                    class="font-bold text-green-600 bg-green-50 px-3 py-1 rounded-full inline-block">
                                                    {{ $trip->available_seats_count }} متاح</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="prose max-w-none">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4 border-r-4 border-safer-blue pr-3">عن
                                    الرحلة</h3>
                                <p
                                    class="text-gray-600 leading-relaxed text-lg bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                                    استمتع برحلة مريحة وآمنة من {{ $trip->departure_location_ar }} إلى
                                    {{ $trip->arrival_location_ar }}.
                                    نوفر لك أفضل خدمة نقل مع حافلات حديثة ومجهزة بأحدث وسائل الراحة لضمان وصولك إلى
                                    وجهتك بأمان وراحة تامة.
                                </p>
                            </div>
                        </div>

                        {{-- Booking Sidebar --}}
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 border border-gray-100 shadow-sm">
                                <div class="mb-6 pb-6 border-b border-gray-200">
                                    <div class="flex items-baseline gap-2 mb-2">
                                        <span
                                            class="text-4xl font-bold text-safer-blue">{{ number_format($trip->price) }}</span>
                                        <span class="text-gray-500">ج.م</span>
                                    </div>
                                    <p class="text-gray-600 text-sm font-medium">سعر الفرد الواحد شامل الضريبة</p>
                                </div>

                                @auth
                                    @if($trip->bus && $trip->available_seats_count > 0 && $trip->trip_date >= now()->toDateString())
                                        <form method="GET" action="{{ route('web.buses.search.results') }}" class="space-y-4">
                                            <input type="hidden" name="departure_location" value="{{ $trip->departure_location_ar }}">
                                            <input type="hidden" name="arrival_location" value="{{ $trip->arrival_location_ar }}">
                                            <input type="hidden" name="trip_date" value="{{ $trip->trip_date->format('Y-m-d') }}">

                                            <div>
                                                <label class="block text-gray-700 font-bold mb-2">عدد المقاعد</label>
                                                <div class="relative">
                                                    <input type="number" name="number_of_passengers" min="1"
                                                        max="{{ $trip->available_seats_count }}" value="1"
                                                        class="w-full border border-gray-200 rounded-xl px-4 py-3 pl-10 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition outline-none text-left"
                                                        dir="ltr">
                                                    <div
                                                        class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                                        <i class="fa-solid fa-users"></i>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                                                <div class="flex justify-between items-center">
                                                    <span class="text-blue-800 font-bold">الإجمالي</span>
                                                    <span class="text-2xl font-bold text-safer-blue"
                                                        id="total-price">{{ number_format($trip->price) }} ج.م</span>
                                                </div>
                                            </div>

                                            <button type="submit"
                                                class="w-full bg-safer-blue text-white px-6 py-4 rounded-xl hover:bg-blue-700 transition text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-200">
                                                احجز الآن
                                            </button>
                                        </form>

                                        <script>
                                            document.querySelector('input[name="number_of_passengers"]').addEventListener('input', function () {
                                                const quantity = parseInt(this.value) || 1;
                                                const pricePerPerson = {{ $trip->price }};
                                                const total = quantity * pricePerPerson;
                                                document.getElementById('total-price').textContent = total.toLocaleString('ar-EG') + ' ج.م';
                                            });
                                        </script>
                                    @elseif(!$trip->bus || $trip->available_seats_count == 0)
                                        <div
                                            class="bg-red-50 border-2 border-red-100 text-red-600 px-6 py-8 rounded-xl text-center">
                                            <div
                                                class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-solid fa-chair text-3xl"></i>
                                            </div>
                                            <p class="font-bold text-lg mb-1">المقاعد نفدت</p>
                                            <p class="text-sm opacity-80">جميع المقاعد في هذه الرحلة محجوزة</p>
                                        </div>
                                    @else
                                        <div
                                            class="bg-gray-100 border-2 border-gray-200 text-gray-600 px-6 py-8 rounded-xl text-center">
                                            <div
                                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-regular fa-calendar-xmark text-3xl"></i>
                                            </div>
                                            <p class="font-bold text-lg mb-1">الرحلة انتهت</p>
                                            <p class="text-sm opacity-80">تاريخ الرحلة قد مضى</p>
                                        </div>
                                    @endif
                                @else
                                    <div
                                        class="bg-yellow-50 border-2 border-yellow-100 text-yellow-800 px-6 py-8 rounded-xl text-center">
                                        <div
                                            class="w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fa-solid fa-user-lock text-3xl text-yellow-600"></i>
                                        </div>
                                        <p class="font-bold text-lg mb-4">سجل دخولك للحجز</p>
                                        <a href="{{ route('login') }}"
                                            class="inline-block w-full bg-safer-blue text-white px-6 py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                            تسجيل الدخول
                                        </a>
                                        <p class="mt-4 text-sm">
                                            ليس لديك حساب؟
                                            <a href="{{ route('register') }}"
                                                class="text-safer-blue font-bold hover:underline">أنشئ حساب جديد</a>
                                        </p>
                                    </div>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>