<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>طلبات الخدمات الخاصة بي | سافر</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #F9FAFB;
        }
        .text-safer-blue { color: #2C67FF; }
        .bg-safer-blue { background-color: #2C67FF; }
    </style>
</head>

<body>
    @include('partials.navbar')

    <main class="py-12 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-12">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 text-right">طلبات الخدمات الخاصة بي</h1>
                    <p class="mt-2 text-sm text-gray-500 text-right">عرض وإدارة الحجوزات الخاصة بالأوتوبيسات والسيارات الخاصة</p>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-8 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl text-right">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-8 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl text-right">
                    {{ session('error') }}
                </div>
            @endif

            @if($requests->isEmpty())
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-50 text-blue-600 mb-4">
                        <i class="fa-solid fa-clipboard-list text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">لا توجد طلبات بعد</h3>
                    <p class="text-gray-500 mb-6">لم تقم بإرسال أي طلبات للخدمات حتى الآن.</p>
                    <a href="{{ route('web.home') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-blue-600 hover:bg-blue-700 transition">
                        استكشف الخدمات
                    </a>
                </div>
            @else
                <div class="grid gap-6">
                    @foreach($requests as $request)
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition group overflow-hidden relative">
                            <div class="flex flex-col lg:flex-row lg:items-center gap-6">
                                <!-- Icon/Thumb -->
                                <div class="w-16 h-16 rounded-2xl flex items-center justify-center shrink-0 {{ $request->service_type === 'private_car' ? 'bg-indigo-50 text-indigo-600' : 'bg-orange-50 text-orange-600' }}">
                                    @if($request->service_type === 'private_car')
                                        <i class="fa-solid fa-car text-2xl"></i>
                                    @else
                                        <i class="fa-solid fa-bus text-2xl"></i>
                                    @endif
                                </div>

                                <!-- Data -->
                                <div class="flex-1 text-right">
                                    <div class="flex flex-wrap items-center justify-end lg:justify-start gap-3 mb-2">
                                        <span class="px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset {{ 
                                            $request->status === 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 
                                            ($request->status === 'approved' ? 'bg-green-50 text-green-700 ring-green-600/20' : 
                                            'bg-red-50 text-red-700 ring-red-600/20') 
                                         }}">
                                            @switch($request->status)
                                                @case('pending') قيد المراجعة @break
                                                @case('approved') تم التأكيد @break
                                                @case('cancelled') ملغى @break
                                                @default {{ $request->status }}
                                            @endswitch
                                        </span>
                                        <h3 class="text-lg font-bold text-gray-900">
                                            {{ $request->service_type === 'private_car' ? 'طلب سيارة خاصة' : 'طلب أوتوبيس' }}
                                            <span class="text-gray-400 font-normal text-sm mr-2">#{{ $request->request_reference }}</span>
                                        </h3>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">تاريخ الحجز</p>
                                            <p class="text-sm font-bold text-gray-700">
                                                @if($request->service_type === 'bus')
                                                    {{ $request->trip_date ? $request->trip_date->format('Y-m-d') : ($request->trip ? $request->trip->trip_date->format('Y-m-d') : '---') }}
                                                    @if($request->trip && $request->trip->trip_time) <span class="text-[10px] text-gray-400 font-normal">({{ $request->trip->trip_time }})</span> @endif
                                                @else
                                                    {{ $request->start_date ? $request->start_date->format('Y-m-d') : '---' }}
                                                    @if($request->start_time) <span class="text-[10px] text-gray-400 font-normal">({{ \Carbon\Carbon::parse($request->start_time)->format('h:i A') }})</span> @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">المسار</p>
                                            <p class="text-sm font-bold text-gray-700 truncate">
                                                {{ $request->departure_location_ar ?: ($request->trip ? $request->trip->departure_location_ar : '---') }} 
                                                <i class="fa-solid fa-arrow-left text-[10px] mx-1 text-gray-300"></i>
                                                {{ $request->arrival_location_ar ?: ($request->trip ? $request->trip->arrival_location_ar : '---') }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">التفاصيل</p>
                                            <p class="text-sm font-bold text-gray-700">
                                                @if($request->service_type === 'bus')
                                                    عدد المقاعد: {{ $request->passengers_count }}
                                                @else
                                                    @if($request->booking_type === 'days')
                                                        يومي
                                                    @elseif($request->booking_type === 'hours')
                                                        بالساعة ({{ $request->duration_hours }} ساعة)
                                                    @else
                                                        ---
                                                    @endif
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">السعر الإجمالي</p>
                                            <p class="text-sm font-bold text-blue-600">{{ number_format($request->total_price) }} ج.م</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center justify-end gap-3 mt-4 lg:mt-0">
                                    <button class="px-5 py-2.5 rounded-xl border border-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">
                                        التفاصيل
                                    </button>
                                    @if($request->status === 'pending')
                                        <button onclick="confirmCancel('{{ route('web.bookings.service_cancel', $request) }}')" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100 transition">
                                            إلغاء
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-8 flex justify-center">
                    {{ $requests->links() }}
                </div>
            @endif
        </div>
    </main>

    @include('partials.footer')

    <form id="cancel-form" method="POST" class="hidden">
        @csrf
    </form>

    <script>
        function confirmCancel(url) {
            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: "لن تتمكن من التراجع عن هذا الإجراء!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'نعم، قم بالإلغاء',
                cancelButtonText: 'تراجع'
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.getElementById('cancel-form');
                    form.action = url;
                    form.submit();
                }
            })
        }
    </script>
</body>

</html>
