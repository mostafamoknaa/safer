<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | {{ app()->getLocale() == 'ar' ? $event->name_ar : $event->name_en }}</title>
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
                {{-- Event Image --}}
                <div class="relative h-64 md:h-96 overflow-hidden group">
                    @php
                        $images = $event->activity_images;
                        $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                        $imageUrl = $firstImage ? asset('storage/' . $firstImage) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800';
                    @endphp
                    <img src="{{ $imageUrl }}" alt="{{ $event->name_ar }}"
                        class="w-full h-full object-cover transform group-hover:scale-105 transition duration-700">

                    <!-- Overlay Gradient -->
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-90">
                    </div>

                    {{-- Date Badge --}}
                    <div
                        class="absolute top-6 right-6 bg-safer-blue text-white px-4 py-2 rounded-xl text-sm font-bold shadow-lg">
                        {{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}
                    </div>

                    <!-- Back Button -->
                    <a href="{{ route('web.events.index') }}"
                        class="absolute top-6 left-6 w-10 h-10 rounded-full bg-white/20 backdrop-blur-md flex items-center justify-center text-white hover:bg-white hover:text-safer-blue transition duration-300">
                        <i class="fa-solid fa-arrow-left"></i>
                    </a>
                </div>

                {{-- Event Details --}}
                <div class="p-6 md:p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Main Content --}}
                        <div class="lg:col-span-2">
                            <h1 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">
                                {{ $event->name_ar }}
                            </h1>

                            <div class="flex flex-wrap gap-4 mb-8">
                                <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                    <i class="fa-solid fa-location-dot text-safer-blue"></i>
                                    <span class="font-medium">{{ $event->location_ar }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                    <i class="fa-solid fa-calendar text-safer-blue"></i>
                                    <span
                                        class="font-medium">{{ \Carbon\Carbon::parse($event->event_date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex items-center gap-2 text-gray-600 bg-gray-50 px-4 py-2 rounded-lg">
                                    <i class="fa-solid fa-ticket text-safer-blue"></i>
                                    <span class="font-medium">{{ $event->available_tickets }} تذكرة متاحة</span>
                                </div>
                            </div>

                            <div class="prose max-w-none mb-10">
                                <h3 class="text-2xl font-bold text-gray-900 mb-4 border-r-4 border-safer-blue pr-3">عن
                                    الفعالية</h3>
                                <p
                                    class="text-gray-600 leading-relaxed text-lg bg-gray-50/50 p-6 rounded-2xl border border-gray-100">
                                    {{ $event->description_ar }}
                                </p>
                            </div>

                            {{-- Additional Images Gallery --}}
                            @if(is_array($event->activity_images) && count($event->activity_images) > 1)
                                <div class="mb-8">
                                    <h3 class="text-2xl font-bold text-gray-900 mb-6 border-r-4 border-safer-blue pr-3">معرض
                                        الصور</h3>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        @foreach(array_slice($event->activity_images, 1, 6) as $image)
                                            <div
                                                class="relative h-48 rounded-xl overflow-hidden group cursor-pointer shadow-sm hover:shadow-md transition">
                                                <img src="{{ asset('storage/' . $image) }}" alt="صورة الفعالية"
                                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-500">
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Booking Sidebar --}}
                        <div class="lg:col-span-1">
                            <div class="bg-gray-50 rounded-2xl p-6 sticky top-24 border border-gray-100 shadow-sm">
                                <div class="mb-6 pb-6 border-b border-gray-200">
                                    <div class="flex items-baseline gap-2 mb-2">
                                        <span
                                            class="text-4xl font-bold text-safer-blue">{{ number_format($event->price_per_person) }}</span>
                                        <span class="text-gray-500">ج.م</span>
                                    </div>
                                    <p class="text-gray-600 text-sm font-medium">سعر الفرد الواحد شامل الضريبة</p>
                                </div>

                                @auth
                                    @if($event->available_tickets > 0 && $event->event_date >= now())
                                        <form method="POST" action="{{ route('web.events.purchase') }}" class="space-y-4">
                                            @csrf
                                            <input type="hidden" name="event_id" value="{{ $event->id }}">

                                            <div>
                                                <label class="block text-gray-700 font-bold mb-2">عدد التذاكر</label>
                                                <div class="relative">
                                                    <input type="number" name="number_of_tickets" min="1"
                                                        max="{{ $event->available_tickets }}" value="1"
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
                                                        id="total-price">{{ number_format($event->price_per_person) }}
                                                        ج.م</span>
                                                </div>
                                            </div>

                                            <button type="submit"
                                                class="w-full bg-safer-blue text-white px-6 py-4 rounded-xl hover:bg-blue-700 transition text-lg font-bold shadow-lg hover:shadow-xl hover:-translate-y-1 transform duration-200">
                                                تأكيد الحجز
                                            </button>
                                        </form>

                                        <script>
                                            document.querySelector('input[name="number_of_tickets"]').addEventListener('input', function () {
                                                const quantity = parseInt(this.value) || 1;
                                                const pricePerPerson = {{ $event->price_per_person }};
                                                const total = quantity * pricePerPerson;
                                                document.getElementById('total-price').textContent = total.toLocaleString('ar-EG') + ' ج.م';
                                            });
                                        </script>
                                    @elseif($event->available_tickets == 0)
                                        <div
                                            class="bg-red-50 border-2 border-red-100 text-red-600 px-6 py-8 rounded-xl text-center">
                                            <div
                                                class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-solid fa-ticket-simple text-3xl"></i>
                                            </div>
                                            <p class="font-bold text-lg mb-1">نفدت التذاكر</p>
                                            <p class="text-sm opacity-80">جميع التذاكر لهذه الفعالية تم بيعها</p>
                                        </div>
                                    @else
                                        <div
                                            class="bg-gray-100 border-2 border-gray-200 text-gray-600 px-6 py-8 rounded-xl text-center">
                                            <div
                                                class="w-16 h-16 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4">
                                                <i class="fa-regular fa-calendar-xmark text-3xl"></i>
                                            </div>
                                            <p class="font-bold text-lg mb-1">انتهت الفعالية</p>
                                            <p class="text-sm opacity-80">تاريخ الفعالية قد مضى</p>
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