<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | تم الدفع بنجاح</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
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

    <div class="container mx-auto px-4 py-12">
        <div class="max-w-2xl mx-auto">
            <div class="text-center mb-8">
                <div class="inline-block p-6 bg-green-100 rounded-full mb-4">
                    <i class="fa-solid fa-check-circle text-6xl text-green-600"></i>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 mb-2">تم الدفع بنجاح!</h1>
                <p class="text-gray-600">تم تأكيد حجزك وإرسال التفاصيل إلى بريدك الإلكتروني</p>
            </div>

            <div class="bg-white rounded-[24px] shadow-lg border border-gray-100 p-8 mb-6">
                <div class="border-b border-gray-200 pb-4 mb-4">
                    <h3 class="font-bold text-gray-900 text-xl mb-2">تفاصيل الحجز</h3>
                    <p class="text-gray-500 text-sm">رقم الحجز: #{{ $serviceRequest->id }}</p>
                </div>

                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-route text-blue-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm">المسار</p>
                            <p class="font-bold text-gray-900">
                                {{ $serviceRequest->trip->departure_location_ar }}
                                <i class="fa-solid fa-arrow-left mx-2 text-blue-600"></i>
                                {{ $serviceRequest->trip->arrival_location_ar }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-calendar text-purple-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm">التاريخ والوقت</p>
                            <p class="font-bold text-gray-900">
                                {{ \Carbon\Carbon::parse($serviceRequest->trip->trip_date)->format('d M Y') }}
                                -
                                {{ \Carbon\Carbon::parse($serviceRequest->trip->trip_time)->format('h:i A') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-chair text-green-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm">المقاعد</p>
                            <p class="font-bold text-gray-900">
                                {{ $serviceRequest->number_of_seats }} مقعد
                                @if($serviceRequest->notes)
                                    - {{ str_replace('المقاعد المحجوزة: ', '', $serviceRequest->notes) }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div
                            class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="fa-solid fa-credit-card text-orange-600"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-gray-500 text-sm">المبلغ المدفوع</p>
                            <p class="font-bold text-gray-900 text-2xl">
                                {{ number_format($serviceRequest->total_price) }} ج.م</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <a href="{{ route('web.services.my-requests') }}"
                    class="block text-center bg-safer-blue text-white px-6 py-4 rounded-xl hover:bg-blue-700 transition font-bold">
                    <i class="fa-solid fa-list ml-2"></i>
                    عرض حجوزاتي
                </a>
                <a href="{{ route('web.home') }}"
                    class="block text-center bg-gray-100 text-gray-700 px-6 py-4 rounded-xl hover:bg-gray-200 transition font-bold">
                    <i class="fa-solid fa-home ml-2"></i>
                    العودة للرئيسية
                </a>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>