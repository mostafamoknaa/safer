<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | تفاصيل الطلب #{{ $request->request_reference }}</title>
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
        .status-approved { background-color: #D1FAE5; color: #065F46; }
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
            align-items: center;
            justify-content: center;
            background: #fff;
            color: #2563eb;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.02);
            flex-shrink: 0;
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="py-12 px-4 md:px-8 max-w-5xl mx-auto min-h-screen">
        <!-- Breadcrumb & Back -->
        <div class="flex items-center justify-between mb-8">
            <a href="{{ route('web.bookings.index', ['tab' => 'services']) }}" class="flex items-center gap-2 text-gray-500 hover:text-blue-600 transition font-bold">
                <i class="fa-solid fa-arrow-right"></i>
                <span>العودة لطلباتي</span>
            </a>
            <div class="flex items-center gap-2 text-sm text-gray-400">
                <a href="{{ route('web.home') }}">الرئيسية</a>
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <a href="{{ route('web.bookings.index') }}">حجوزاتي</a>
                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                <span class="text-gray-900 font-bold">تفاصيل الطلب</span>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Header Info -->
                <div class="booking-card p-8 text-right">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8">
                        <div>
                            <span class="status-badge status-{{ $request->status }}">
                                @switch($request->status)
                                    @case('pending') قيد المراجعة @break
                                    @case('approved') تم التأكيد @break
                                    @case('cancelled') ملغى @break
                                    @case('completed') مكتمل @break
                                    @default {{ $request->status }}
                                @endswitch
                            </span>
                        </div>
                        <div class="md:text-left">
                            <p class="text-gray-400 text-sm mb-1 uppercase tracking-wider font-bold">إجمالي المبلغ</p>
                            <p class="text-4xl font-bold text-blue-600">{{ number_format($request->total_price) }} <span class="text-lg">ج.م</span></p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-calendar-day"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">تاريخ البداية</p>
                                <p class="font-bold text-gray-900">{{ $request->start_date ? $request->start_date->translatedFormat('d F Y') : ($request->trip_date ? $request->trip_date->translatedFormat('d F Y') : '---') }}</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-clock"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">وقت التحرك</p>
                                <p class="font-bold text-gray-900">{{ $request->start_time ?: ($request->trip ? $request->trip->trip_time : '---') }}</p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-map-location-dot"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">المسار</p>
                                <p class="font-bold text-gray-900 text-sm">
                                    {{ $request->departure_location_ar }} 
                                    <i class="fa-solid fa-arrow-left text-[10px] mx-1 text-gray-300"></i>
                                    {{ $request->arrival_location_ar }}
                                </p>
                            </div>
                        </div>
                        <div class="detail-item">
                            <div class="detail-icon"><i class="fa-solid fa-users"></i></div>
                            <div>
                                <p class="text-xs text-gray-400 mb-0.5">عدد الركاب / التذاكر</p>
                                <p class="font-bold text-gray-900">
                                    @if($request->service_type === 'bus')
                                        {{ $request->bookedSeats->count() }} تذكرة
                                    @else
                                        {{ $request->passengers_count ?: 1 }} فرد
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                @if($request->service_type === 'bus')
                <!-- Bus Trip Breakdown -->
                <div class="booking-card p-8 text-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">تفاصيل الرحلة</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                            <div class="w-16 h-16 rounded-xl bg-orange-50 text-orange-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-bus text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">رحلة أوتوبيس</h4>
                                <p class="text-xs text-gray-400">{{ $request->trip ? $request->trip->bus->name : 'أوتوبيس سياحي' }}</p>
                            </div>
                            <div class="text-left">
                                <p class="font-bold text-gray-900">{{ $request->bookedSeats->pluck('seat_number')->implode(', ') }}</p>
                                <p class="text-[10px] text-gray-400">أرقام المقاعد</p>
                            </div>
                        </div>
                    </div>
                </div>
                @elseif($request->service_type === 'private_car')
                <!-- Private Car Breakdown -->
                <div class="booking-card p-8 text-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-6">تفاصيل السيارة</h3>
                    <div class="space-y-4">
                        <div class="flex items-center gap-4 p-4 rounded-2xl border border-gray-100 bg-gray-50/50">
                            <div class="w-16 h-16 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                                <i class="fa-solid fa-car text-2xl"></i>
                            </div>
                            <div class="flex-1">
                                <h4 class="font-bold text-gray-900">{{ $request->privateCar ? $request->privateCar->name_ar : 'سيارة خاصة' }}</h4>
                                <p class="text-xs text-gray-400">{{ $request->booking_type === 'days' ? 'حجز يومي' : 'حجز بالساعة' }}</p>
                            </div>
                            @if($request->duration_hours)
                            <div class="text-left">
                                <p class="font-bold text-gray-900">{{ $request->duration_hours }} ساعة</p>
                                <p class="text-[10px] text-gray-400">المدة</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                @if($request->notes)
                <!-- Notes -->
                <div class="booking-card p-8 text-right">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">ملاحظات إضافية</h3>
                    <div class="bg-gray-50 p-6 rounded-2xl text-gray-600 leading-relaxed italic">
                        " {{ $request->notes }} "
                    </div>
                </div>
                @endif
            </div>

            <!-- Sidebar -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Status & Payment -->
                <div class="booking-card p-6 text-right">
                    <h3 class="font-bold text-gray-900 mb-4">ملخص الدفع</h3>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="font-bold text-gray-900">{{ number_format($request->total_price) }} ج.م</span>
                            <span class="text-gray-400">الإجمالي</span>
                        </div>
                        @if($request->status === 'approved')
                        <div class="pt-3 border-t border-dashed flex items-center justify-between">
                            <span class="font-bold text-xl text-blue-600">{{ number_format($request->total_price) }} ج.م</span>
                            <span class="text-gray-900 font-bold">المتبقي</span>
                        </div>
                        <button class="w-full mt-6 bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100">
                            اكمل الدفع الآن
                        </button>
                        @else
                        <div class="bg-gray-50 p-4 rounded-xl text-center text-sm text-gray-500 mt-4 italic">
                            سيتم تفعيل خيار الدفع فور تأكيد الحجز من قبل الإدارة
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Type Badge -->
                <div class="booking-card p-6 text-right">
                    <div class="flex items-center justify-end gap-3">
                        <div>
                            <p class="font-bold text-gray-900">{{ $request->service_type === 'private_car' ? 'خدمة السيارات الخاصة' : 'خدمة النقل الجماعي' }}</p>
                            <p class="text-xs text-gray-400">نوع الخدمة</p>
                        </div>
                        <div class="w-12 h-12 rounded-xl {{ $request->service_type === 'private_car' ? 'bg-indigo-50 text-indigo-600' : 'bg-orange-50 text-orange-600' }} flex items-center justify-center shrink-0">
                            <i class="fa-solid {{ $request->service_type === 'private_car' ? 'fa-car' : 'fa-bus' }} text-xl"></i>
                        </div>
                    </div>
                </div>

                <!-- Support -->
                <div class="bg-blue-600 rounded-[32px] p-6 text-white text-right relative overflow-hidden">
                    <div class="relative z-10">
                        <h3 class="font-bold text-lg mb-2">هل لديك استفسار؟</h3>
                        <p class="text-blue-100 text-sm mb-6">يسعدنا خدمتك والرد على جميع استفساراتك حول الطلب</p>
                        <a href="{{ route('web.contact') }}" class="inline-flex items-center gap-2 bg-white text-blue-600 px-6 py-2.5 rounded-xl font-bold hover:bg-blue-50 transition">
                            <span>تواصل معنا</span>
                            <i class="fa-solid fa-headset"></i>
                        </a>
                    </div>
                    <i class="fa-solid fa-headset absolute -left-4 -bottom-4 text-8xl text-blue-500/20"></i>
                </div>
            </div>
        </div>
    </main>

    @include('partials.footer')
</body>

</html>
