<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | تفاصيل الحجز #{{ $booking->booking_reference }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
            background-color: #F8F9FB;
        }

        .booking-card {
            border-radius: 32px;
            background: #fff;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
        }

        .status-badge {
            padding: 0.5rem 1.5rem;
            border-radius: 100px;
            font-weight: 700;
            font-size: 0.875rem;
        }

        .status-pending { background-color: #FEF3C7; color: #92400E; }
        .status-confirmed { background-color: #D1FAE5; color: #065F46; }
        .status-cancelled { background-color: #FEE2E2; color: #991B1B; }
        .status-completed { background-color: #DBEAFE; color: #1E40AF; }

        .detail-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #F8F9FB;
            border-radius: 20px;
        }

        .detail-icon {
            width: 44px;
            height: 44px;
            border-radius: 14px;
            display: flex;
            items-center: center;
            justify-content: center;
            background: #fff;
            color: #2563eb;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    <!-- Alerts Wrapper -->
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] w-[90%] max-w-md space-y-3">
        @if(session('success'))
            <div class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce">
                <i class="fas fa-check-circle"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif
        @if(session('info'))
            <div class="bg-blue-100 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-info-circle"></i>
                <p class="font-bold">{{ session('info') }}</p>
            </div>
        @endif
    </div>

    @include('partials.navbar')

    <main class="py-12 px-4 md:px-8 max-w-5xl mx-auto min-h-screen">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('web.bookings.index') }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition font-bold">
                <i class="fa-solid fa-arrow-right"></i>
                <span>العودة لحجوزاتي</span>
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ route('web.home') }}">الرئيسية</a>
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <a href="{{ route('web.bookings.index') }}">حجوزاتي</a>
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span class="text-gray-900 font-bold">تفاصيل الحجز</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Header Info -->
                <div class="booking-card p-8 text-right">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <span class="status-badge status-{{ $booking->status }}">
                                @switch($booking->status)
                                    @case('pending') قيد المراجعة @break
                                    @case('confirmed') تم التأكيد @break
                                    @case('cancelled') ملغى @break
                                    @case('completed') مكتمل @break
                                    @default {{ $booking->status }}
                                @endswitch
                            </span>
                        </div>
                        <div class="md:text-left">
                            <p class="text-gray-400 text-sm mb-1 uppercase tracking-wider font-bold">إجمالي المبلغ</p>
                            <p class="text-4xl font-bold text-blue-600">{{ number_format($booking->total_price) }} <span class="text-lg">ج.م</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-calendar-check"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">تاريخ الوصول</p>
                                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->check_in_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-calendar-xmark"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">تاريخ المغادرة</p>
                                <p class="font-bold text-gray-900">{{ \Carbon\Carbon::parse($booking->check_out_date)->translatedFormat('d F Y') }}</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-moon"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">عدد الليالي</p>
                                <p class="font-bold text-gray-900">{{ $booking->nights_count }} ليلة</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">عدد الضيوف</p>
                                <p class="font-bold text-gray-900">{{ $booking->guests_count }} ضيف</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Rooms Breakdown -->
                <div class="booking-card p-8 text-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">تفاصيل الغرف</h3>
                    <div class="space-y-4">
                        @foreach($booking->bookedRooms as $bookedRoom)
                            @php $room = $bookedRoom->room; @endphp
                            <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100">
                                <div class="w-16 h-16 rounded-xl overflow-hidden shrink-0">
                                    <img src="{{ $room->media->first() ? $room->media->first()->file_url : 'https://images.unsplash.com/photo-1590490360182-c33d57733427?w=200' }}" 
                                         class="w-full h-full object-cover">
                                </div>
                                <div class="flex-1">
                                    <h4 class="font-bold text-gray-900">{{ $room->name }}</h4>
                                    <p class="text-xs text-gray-400">{{ $room->beds_count }} سرير | {{ $room->bathrooms_count }} حمام</p>
                                </div>
                                <div class="text-left">
                                    <p class="font-bold text-blue-600">{{ number_format($room->price_per_night) }} ج.م</p>
                                    <p class="text-[10px] text-gray-400">للغرفة في الليلة</p>
                                </div>
                                @if(isset($bookedRoom->rooms_count))
                                    <div class="px-3 py-1 bg-gray-100 rounded-lg font-bold text-sm">
                                        {{ $bookedRoom->rooms_count }} غرف
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                @if($booking->notes)
                <!-- Notes -->
                <div class="booking-card p-8 text-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">طلبات خاصة</h3>
                    <div class="bg-gray-50 p-6 rounded-2xl text-gray-600 leading-relaxed italic">
                        " {{ $booking->notes }} "
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Hotel Info -->
                <div class="booking-card overflow-hidden">
                    <div class="h-48 relative">
                        <img src="{{ $booking->hotel->media->first() ? $booking->hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600' }}" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 to-transparent"></div>
                        <div class="absolute bottom-4 right-4 text-right">
                            <h3 class="text-white font-bold text-xl">{{ $booking->hotel->name_ar }}</h3>
                            <div class="flex items-center justify-end gap-1 mt-1">
                                @for($i = 0; $i < 5; $i++)
                                    <i class="fa-solid fa-star text-[10px] {{ $i < round($booking->hotel->rate ?? 5) ? 'text-yellow-400' : 'text-gray-400/50' }}"></i>
                                @endfor
                            </div>
                        </div>
                    </div>
                    <div class="p-6 text-right space-y-4">
                        <div class="flex items-center justify-end gap-3 text-gray-500">
                            <span>{{ $booking->hotel->location_ar ?? $booking->hotel->province->name_ar }}</span>
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <a href="{{ route('web.hotels.show', $booking->hotel) }}" class="block w-full py-3 bg-gray-50 text-gray-700 rounded-xl font-bold text-center hover:bg-blue-50 hover:text-blue-600 transition">تحميل الفندق</a>
                    </div>
                </div>

                <!-- Payment Status -->
                <div class="booking-card p-6 text-right">
                    <h3 class="font-bold text-gray-900 mb-4">ملخص الدفع</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-gray-900">{{ number_format($booking->total_price) }} ج.م</span>
                            <span class="text-gray-400">الإجمالي</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-green-600">0 ج.م</span>
                            <span class="text-gray-400">المبلغ المدفوع</span>
                        </div>
                        <div class="pt-3 border-t border-dashed flex items-center justify-between">
                            <span class="font-bold text-xl text-blue-600">{{ number_format($booking->total_price) }} ج.م</span>
                            <span class="text-gray-900 font-bold">المتبقي</span>
                        </div>
                    </div>
                    @if($booking->status === 'confirmed')
                        <a href="{{ route('web.payments.initiate', ['type' => 'booking', 'id' => $booking->id]) }}" 
                           class="block w-full mt-6 bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 text-center">
                            اكمل الدفع الآن
                        </a>
                    @else
                        <div class="bg-gray-50 p-4 rounded-xl text-center text-sm text-gray-500 mt-4 italic">
                            سيتم تفعيل خيار الدفع فور تأكيد الحجز من قبل الإدارة
                        </div>
                    @endif
                </div>

                <!-- Support -->
                <div class="bg-indigo-600 rounded-[32px] p-6 text-white text-right relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-2">هل تحتاج مساعدة؟</h3>
                        <p class="text-indigo-100 text-sm mb-6">فريق الدعم الفني متواجد دائماً لمساعدتك في أي وقت</p>
                        <a href="{{ route('web.contact') }}" class="inline-flex items-center gap-2 bg-white text-indigo-600 px-6 py-2.5 rounded-xl font-bold hover:bg-indigo-50 transition">
                            <span>تواصل معنا</span>
                            <i class="fa-solid fa-headset"></i>
                        </a>
                    </div>
                    <i class="fa-solid fa-circle-question absolute -left-4 -bottom-4 text-8xl text-indigo-500/20"></i>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>
