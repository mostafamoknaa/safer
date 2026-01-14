<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | حجز حافلة</title>
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
        <div class="max-w-4xl mx-auto">
            <div class="text-center mb-10">
                <h1 class="text-4xl font-bold text-gray-900 mb-3">حجز حافلة</h1>
                <p class="text-gray-600">ابحث عن الحافلات المتاحة واحجز رحلتك بسهولة</p>
            </div>

            <div class="bg-white rounded-[24px] shadow-lg border border-gray-100 p-8">
                <form method="POST" action="{{ route('web.buses.search.results') }}" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-right">محطة البداية</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" 
                                    name="departure_location" 
                                    placeholder="ابحث عن محطة البداية"
                                    required
                                    class="w-full border border-gray-300 rounded-xl px-12 py-4 text-right focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-right">محطة النهاية</label>
                            <div class="relative">
                                <i class="fa-solid fa-location-dot absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="text" 
                                    name="arrival_location" 
                                    placeholder="ابحث عن محطة النهاية"
                                    required
                                    class="w-full border border-gray-300 rounded-xl px-12 py-4 text-right focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-right">تاريخ الرحلة</label>
                            <div class="relative">
                                <i class="fa-solid fa-calendar absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="date" 
                                    name="trip_date" 
                                    min="{{ date('Y-m-d') }}"
                                    required
                                    class="w-full border border-gray-300 rounded-xl px-12 py-4 text-right focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>

                        <div>
                            <label class="block text-gray-700 font-bold mb-2 text-right">عدد الركاب</label>
                            <div class="relative">
                                <i class="fa-solid fa-users absolute right-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
                                <input type="number" 
                                    name="number_of_passengers" 
                                    min="1" 
                                    max="50"
                                    value="1"
                                    required
                                    class="w-full border border-gray-300 rounded-xl px-12 py-4 text-right focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <div class="pt-4">
                        <button type="submit" 
                            class="w-full bg-safer-blue text-white px-8 py-4 rounded-xl hover:bg-blue-700 transition text-lg font-bold shadow-lg hover:shadow-xl">
                            <i class="fa-solid fa-magnifying-glass ml-2"></i>
                            بحث عن حافلات
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-12">
                <div class="bg-blue-50 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-shield-halved text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">رحلات آمنة</h3>
                    <p class="text-gray-600 text-sm">جميع حافلاتنا مؤمنة ومجهزة بأحدث معايير السلامة</p>
                </div>
                <div class="bg-green-50 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-clock text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">مواعيد دقيقة</h3>
                    <p class="text-gray-600 text-sm">نلتزم بمواعيد الانطلاق والوصول المحددة</p>
                </div>
                <div class="bg-purple-50 rounded-2xl p-6 text-center">
                    <div class="w-16 h-16 bg-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fa-solid fa-hand-holding-dollar text-white text-2xl"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2">أسعار منافسة</h3>
                    <p class="text-gray-600 text-sm">أفضل الأسعار مع خدمة عالية الجودة</p>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

</body>

</html>