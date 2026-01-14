@extends('layouts.web')

@section('title', 'السيارات الخاصة')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">السيارات الخاصة</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($privateCars as $car)
                <div class="bg-white rounded-lg shadow-md overflow-hidden">
                    @if($car->image)
                        <img src="{{ Storage::url($car->image) }}"
                            alt="{{ app()->getLocale() == 'ar' ? $car->name_ar : $car->name_en }}" class="w-full h-48 object-cover">
                    @else
                        <div class="w-full h-48 bg-gray-200 flex items-center justify-center">
                            <span class="text-gray-400">لا توجد صورة</span>
                        </div>
                    @endif

                    <div class="p-4">
                        <h3 class="text-xl font-semibold mb-2">
                            {{ app()->getLocale() == 'ar' ? $car->name_ar : $car->name_en }}
                        </h3>
                        <p class="text-gray-600 mb-2">
                            {{ app()->getLocale() == 'ar' ? $car->description_ar : $car->description_en }}
                        </p>
                        <div class="flex items-center gap-4 text-sm text-gray-600 mb-4">
                            <span>👥 السعة: {{ $car->capacity }} شخص</span>
                        </div>

                        <p class="text-2xl font-bold text-blue-600 mb-4">
                            {{ number_format($car->price_per_day) }} ريال
                        </p>
                        <p class="text-sm text-gray-500 mb-4">لليوم الواحد</p>

                        @auth
                            <button
                                onclick="openBookingModal({{ $car->id }}, '{{ app()->getLocale() == 'ar' ? $car->name_ar : $car->name_en }}')"
                                class="block w-full text-center bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                                احجز الآن
                            </button>
                        @else
                            <a href="{{ route('login') }}"
                                class="block w-full text-center bg-gray-600 text-white py-2 rounded-lg hover:bg-gray-700 transition">
                                سجل دخول للحجز
                            </a>
                        @endauth
                    </div>
                </div>
            @empty
                <div class="col-span-3 text-center py-12 text-gray-500">
                    لا توجد سيارات متاحة حالياً
                </div>
            @endforelse
        </div>

        <!-- Booking Modal -->
        @auth
            <div id="bookingModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full mx-4">
                    <h2 class="text-2xl font-bold mb-4">حجز سيارة خاصة</h2>
                    <p id="carName" class="text-gray-600 mb-4"></p>

                    <form method="POST" action="{{ route('web.services.private-car-request') }}">
                        @csrf
                        <input type="hidden" name="private_car_id" id="carId">

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">موقع الاستلام</label>
                            <input type="text" name="pickup_location" required class="w-full border rounded-lg px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">الوجهة</label>
                            <input type="text" name="destination" required class="w-full border rounded-lg px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">تاريخ الاستلام</label>
                            <input type="date" name="pickup_date" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                class="w-full border rounded-lg px-4 py-2">
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 mb-2">ملاحظات (اختياري)</label>
                            <textarea name="notes" rows="3" class="w-full border rounded-lg px-4 py-2"></textarea>
                        </div>

                        <div class="flex gap-2">
                            <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                                تأكيد الحجز
                            </button>
                            <button type="button" onclick="closeBookingModal()"
                                class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg hover:bg-gray-400">
                                إلغاء
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endauth
    </div>

    @push('scripts')
        <script>
            function openBookingModal(carId, carName) {
                document.getElementById('carId').value = carId;
                document.getElementById('carName').textContent = carName;
                document.getElementById('bookingModal').classList.remove('hidden');
            }

            function closeBookingModal() {
                document.getElementById('bookingModal').classList.add('hidden');
            }
        </script>
    @endpush
@endsection