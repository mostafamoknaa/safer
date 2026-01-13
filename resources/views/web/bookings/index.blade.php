@extends('layouts.web')

@section('title', 'حجوزاتي')

@section('content')
    <div class="container mx-auto px-4">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">حجوزاتي</h1>

        <div class="space-y-4">
            @forelse($bookings as $booking)
                <div class="bg-white rounded-lg shadow-md p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex-1">
                            <h3 class="text-xl font-semibold mb-2">
                                {{ app()->getLocale() == 'ar' ? $booking->room->hotel->name_ar : $booking->room->hotel->name_en }}
                            </h3>
                            <p class="text-gray-600 mb-1">
                                الغرفة: {{ app()->getLocale() == 'ar' ? $booking->room->name_ar : $booking->room->name_en }}
                            </p>
                            <p class="text-gray-600 mb-1">
                                📅 تسجيل الدخول: {{ \Carbon\Carbon::parse($booking->check_in_date)->format('Y-m-d') }}
                            </p>
                            <p class="text-gray-600 mb-1">
                                📅 تسجيل الخروج: {{ \Carbon\Carbon::parse($booking->check_out_date)->format('Y-m-d') }}
                            </p>
                            <p class="text-gray-600">
                                👥 عدد الضيوف: {{ $booking->number_of_guests }}
                            </p>
                        </div>
                        <div class="text-left mr-4">
                            <p class="text-2xl font-bold text-blue-600 mb-2">
                                {{ number_format($booking->total_price) }} ريال
                            </p>
                            <span class="inline-block px-3 py-1 rounded-full text-sm font-semibold
                                    @if($booking->status == 'confirmed') bg-green-100 text-green-800
                                    @elseif($booking->status == 'pending') bg-yellow-100 text-yellow-800
                                    @elseif($booking->status == 'cancelled') bg-red-100 text-red-800
                                    @else bg-gray-100 text-gray-800
                                    @endif">
                                @if($booking->status == 'confirmed') مؤكد
                                @elseif($booking->status == 'pending') قيد الانتظار
                                @elseif($booking->status == 'cancelled') ملغي
                                @elseif($booking->status == 'completed') مكتمل
                                @else {{ $booking->status }}
                                @endif
                            </span>
                        </div>
                    </div>

                    <div class="flex gap-2 mt-4">
                        <a href="{{ route('web.bookings.show', $booking) }}"
                            class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                            عرض التفاصيل
                        </a>

                        @if($booking->status != 'cancelled' && $booking->status != 'completed')
                            <form method="POST" action="{{ route('web.bookings.cancel', $booking) }}" class="inline">
                                @csrf
                                <button type="submit" onclick="return confirm('هل أنت متأكد من إلغاء هذا الحجز؟')"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    إلغاء الحجز
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <p class="text-gray-500 text-lg mb-4">لا توجد حجوزات بعد</p>
                    <a href="{{ route('web.hotels.index') }}"
                        class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                        تصفح الفنادق
                    </a>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($bookings->hasPages())
            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        @endif
    </div>
@endsection