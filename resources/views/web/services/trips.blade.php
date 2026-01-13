@extends('layouts.web')

@section('title', 'الرحلات السياحية')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">الرحلات السياحية</h1>

        <!-- Filter -->
        <div class="bg-white rounded-lg shadow-md p-4 mb-8">
            <form method="GET" action="{{ route('web.services.trips') }}">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 mb-2">التاريخ</label>
                        <input type="date" name="date" value="{{ request('date') }}"
                            class="w-full border rounded-lg px-4 py-2">
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">المحافظة</label>
                        <select name="province_id" class="w-full border rounded-lg px-4 py-2">
                            <option value="">الكل</option>
                            <!-- Add provinces here if needed -->
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">
                            بحث
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Trips List -->
        <div class="space-y-4">
            @forelse($trips as $trip)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">
                                {{ app()->getLocale() == 'ar' ? $trip->bus->name_ar : $trip->bus->name_en }}
                            </h3>
                            <p class="text-gray-600 mb-2">
                                📍 الوجهة:
                                {{ $trip->province ? (app()->getLocale() == 'ar' ? $trip->province->name_ar : $trip->province->name_en) : '' }}
                            </p>
                            <p class="text-gray-600 mb-2">
                                🕐 وقت المغادرة: {{ \Carbon\Carbon::parse($trip->departure_time)->format('Y-m-d H:i') }}
                            </p>
                            <p class="text-gray-600 mb-2">
                                👥 المقاعد المتاحة: {{ $trip->available_seats }}
                            </p>
                        </div>
                        <div class="text-left mr-4">
                            <p class="text-2xl font-bold text-blue-600 mb-2">
                                {{ number_format($trip->price_per_seat) }} ريال
                            </p>
                            <p class="text-sm text-gray-500 mb-3">للمقعد الواحد</p>

                            @auth
                                @if($trip->available_seats > 0)
                                    <a href="{{ route('web.services.trips.show', $trip) }}"
                                        class="block bg-blue-600 text-white px-6 py-2 rounded-lg hover:bg-blue-700 transition text-center">
                                        احجز الآن
                                    </a>
                                @else
                                    <span class="block bg-gray-400 text-white px-6 py-2 rounded-lg text-center">
                                        مكتمل
                                    </span>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="block bg-gray-600 text-white px-6 py-2 rounded-lg hover:bg-gray-700 transition text-center">
                                    سجل دخول للحجز
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center text-gray-500">
                    لا توجد رحلات متاحة
                </div>
            @endforelse
        </div>
    </div>
@endsection