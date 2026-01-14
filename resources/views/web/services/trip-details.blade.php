@extends('layouts.web')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto bg-white rounded-3xl overflow-hidden shadow-lg border border-gray-100">
            <!-- Header Image -->
            <div class="relative h-96">
                <img src="https://images.unsplash.com/photo-1544620347-c4fd4a3d5957?w=1200"
                    alt="{{ $trip->arrival_location_ar }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                <div class="absolute bottom-0 right-0 p-8 text-white w-full">
                    <div class="flex items-center justify-between">
                        <div>
                            <h1 class="text-4xl font-bold mb-2">رحلة إلى {{ $trip->arrival_location_ar }}</h1>
                            <p class="text-xl opacity-90"><i class="fa-solid fa-bus ml-2"></i>من
                                {{ $trip->departure_location_ar }}</p>
                        </div>
                        <div class="text-left">
                            <div class="text-3xl font-bold text-yellow-400">{{ number_format($trip->price) }} ج.م</div>
                            <span class="text-sm opacity-80">للفرد</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 p-8">
                <!-- Main Details -->
                <div class="md:col-span-2 space-y-8">
                    <!-- Trip Info Cards -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fa-regular fa-calendar text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">تاريخ الرحلة</p>
                                <p class="font-bold text-gray-900">{{ $trip->trip_date->translatedFormat('l, d F Y') }}</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fa-regular fa-clock text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">وقت الانطلاق</p>
                                <p class="font-bold text-gray-900">
                                    {{ \Carbon\Carbon::parse($trip->trip_time)->format('h:i A') }}</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-hourglass-half text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">مدة الرحلة</p>
                                <p class="font-bold text-gray-900">{{ $trip->duration_minutes }} دقيقة</p>
                            </div>
                        </div>
                        <div class="bg-blue-50 p-4 rounded-xl flex items-center gap-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-blue-600">
                                <i class="fa-solid fa-chair text-xl"></i>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">المقاعد المتاحة</p>
                                <p class="font-bold text-gray-900">{{ $trip->available_seats_count ?? 'غير محدد' }} مقعد</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description (if any) -->
                    @if($trip->description_ar)
                        <div>
                            <h2 class="text-2xl font-bold mb-4">عن الرحلة</h2>
                            <p class="text-gray-600 leading-relaxed">{{ $trip->description_ar }}</p>
                        </div>
                    @endif
                </div>

                <!-- Booking Section -->
                <div class="md:col-span-1">
                    <div class="bg-white border border-gray-200 rounded-2xl p-6 sticky top-24 shadow-sm">
                        <h3 class="text-xl font-bold mb-6">حجز الرحلة</h3>
                        <form action="{{ route('web.services.buses.request') }}" method="POST">
                            @csrf
                            <input type="hidden" name="trip_id" value="{{ $trip->id }}">

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">عدد المقاعد</label>
                                <div class="flex items-center border border-gray-300 rounded-xl overflow-hidden">
                                    <button type="button" onclick="decrementSeats()"
                                        class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 border-l">-</button>
                                    <input type="number" name="number_of_seats" id="seats_input" value="1" min="1"
                                        max="{{ $trip->available_seats_count ?? 10 }}"
                                        class="w-full text-center py-2 focus:outline-none" readonly>
                                    <button type="button" onclick="incrementSeats()"
                                        class="px-4 py-2 bg-gray-50 hover:bg-gray-100 text-gray-600 border-r">+</button>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="block text-gray-700 text-sm font-bold mb-2">مكان الالتقاء (اختياري)</label>
                                <input type="text" name="pickup_location"
                                    class="w-full border border-gray-300 rounded-xl px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                                    placeholder="حدد مكان الالتقاء المفضل">
                            </div>

                            <div class="mb-6">
                                <div class="flex justify-between items-center mb-2">
                                    <span class="text-gray-600">السعر الإجمالي</span>
                                    <span class="font-bold text-lg" id="total_price">{{ number_format($trip->price) }}
                                        ج.م</span>
                                </div>
                            </div>

                            <button type="submit"
                                class="w-full bg-blue-600 text-white py-3 rounded-xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                تأكيد الحجز
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const pricePerSeat = {{ $trip->price }};
        const maxSeats = {{ $trip->available_seats_count ?? 10 }};

        function updateTotalPrice() {
            const seats = parseInt(document.getElementById('seats_input').value);
            const total = seats * pricePerSeat;
            document.getElementById('total_price').innerText = new Intl.NumberFormat('ar-EG').format(total) + ' ج.م';
        }

        function incrementSeats() {
            const input = document.getElementById('seats_input');
            if (parseInt(input.value) < maxSeats) {
                input.value = parseInt(input.value) + 1;
                updateTotalPrice();
            }
        }

        function decrementSeats() {
            const input = document.getElementById('seats_input');
            if (parseInt(input.value) > 1) {
                input.value = parseInt(input.value) - 1;
                updateTotalPrice();
            }
        }
    </script>
@endsection