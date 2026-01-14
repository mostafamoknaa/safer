<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | حدد طريقة الدفع</title>
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
            <div class="mb-8 text-center">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">حدد طريقة الدفع</h1>
                <p class="text-gray-600">اختر طريقة الدفع المناسبة لك</p>
            </div>

            <div class="bg-white rounded-[24px] shadow-lg border border-gray-100 p-8">
                <div class="bg-gray-50 rounded-xl p-6 mb-8">
                    <h3 class="font-bold text-gray-900 mb-4">ملخص الحجز</h3>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">من</span>
                            <span class="font-bold">{{ $trip->departure_location_ar }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">إلى</span>
                            <span class="font-bold">{{ $trip->arrival_location_ar }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">التاريخ</span>
                            <span
                                class="font-bold">{{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">عدد المقاعد</span>
                            <span class="font-bold">{{ $bookingData['number_of_passengers'] }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">المقاعد</span>
                            <span class="font-bold">{{ implode(', ', $bookingData['selected_seats']) }}</span>
                        </div>
                        <div class="border-t border-gray-200 pt-2 mt-2"></div>
                        <div class="flex justify-between text-lg">
                            <span class="text-gray-900 font-bold">الإجمالي</span>
                            <span class="text-safer-blue font-bold">{{ number_format($bookingData['total_price']) }}
                                ج.م</span>
                        </div>
                    </div>
                </div>

                <form method="POST" action="{{ route('web.buses.process-payment') }}">
                    @csrf

                    <div class="space-y-4 mb-8">
                        <label
                            class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="mastercard" required
                                    class="w-5 h-5 text-blue-600">
                                <span class="font-bold text-gray-900">ماستركارد</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 bg-red-500 rounded-full"></div>
                                <div class="w-8 h-8 bg-orange-500 rounded-full -ml-4"></div>
                            </div>
                        </label>

                        <label
                            class="flex items-center justify-between p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-500 transition">
                            <div class="flex items-center gap-4">
                                <input type="radio" name="payment_method" value="visa" required
                                    class="w-5 h-5 text-blue-600">
                                <span class="font-bold text-gray-900">فيزا</span>
                            </div>
                            <div class="text-2xl font-bold text-blue-600">VISA</div>
                        </label>
                    </div>

                    <button type="submit"
                        class="w-full bg-safer-blue text-white px-6 py-4 rounded-xl hover:bg-blue-700 transition text-lg font-bold shadow-lg hover:shadow-xl">
                        تأكيد الدفع
                    </button>
                </form>
            </div>

            <div class="mt-6 text-center">
                <p class="text-sm text-gray-500">
                    <i class="fa-solid fa-lock ml-1"></i>
                    جميع المعاملات مؤمنة ومشفرة
                </p>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>