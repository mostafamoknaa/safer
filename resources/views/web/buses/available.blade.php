<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | الحافلات المتاحة</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
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

    <div class="container mx-auto px-4 py-32 min-h-screen">
        <div class="max-w-5xl mx-auto">
            
            {{-- Header --}}
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-extrabold text-gray-900 mb-2">الحافلات</h1>
                    <p class="text-gray-500 font-medium">
                        {{ $searchData['departure_location'] }} 
                        <i class="fa-solid fa-arrow-left mx-2 text-sm"></i>
                        {{ $searchData['arrival_location'] }}
                    </p>
                </div>
                {{-- Date Badge --}}
                <div class="bg-white px-6 py-3 rounded-2xl shadow-sm border border-gray-100 font-bold text-gray-900 flex items-center gap-3">
                    <i class="fa-regular fa-calendar text-safer-blue"></i>
                    {{ \Carbon\Carbon::parse($searchData['trip_date'])->format('d M Y') }}
                </div>
            </div>

            @if($trips->count() > 0)
                <div class="space-y-6">
                    @foreach($trips as $trip)
                        {{-- Card Container --}}
                        <div class="bg-white rounded-[24px] p-0 shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group">
                            <div class="flex flex-col lg:flex-row items-stretch">
                                
                                {{-- Section 1: Price & Action (Left in RTL, but flex-row means it appears last in code order for RTL usually, let's explicit order) --}}
                                {{-- Actually in visual design: Left is Price/Button, Center is Route, Right is Company. 
                                     In RTL: Right is First, Left is Last. 
                                     So Order: Company -> Route -> Price --}}

                                {{-- Right Section: Company Info --}}
                                <div class="w-full lg:w-1/4 p-6 flex flex-col items-center justify-center border-b lg:border-b-0 lg:border-l border-gray-100">
                                    {{-- Logo --}}
                                    <div class="mb-3">
                                        <i class="fa-brands fa-envira text-5xl text-green-500"></i> {{-- Placeholder for "Go Bus" logo style --}}
                                    </div>
                                    <h3 class="font-extrabold text-gray-900 text-lg mb-1">{{ $trip->bus->name_ar ?? $trip->bus->name_en ?? 'حافلة' }}</h3>
                                    <span class="text-gray-400 text-sm font-medium">{{ $trip->bus->type ?? 'مكيف' }}</span>
                                </div>

                                {{-- Center Section: Route & Timing --}}
                                <div class="flex-1 p-6 flex flex-col justify-center items-center text-center border-b lg:border-b-0 lg:border-l border-gray-100">
                                    <div class="mb-4">
                                        <h4 class="font-bold text-gray-700 text-lg">
                                            {{ $trip->departure_location_ar }} 
                                            <span class="text-gray-300 mx-2">←</span> 
                                            {{ $trip->arrival_location_ar }}
                                        </h4>
                                    </div>
                                    
                                    <div class="flex items-center gap-6 text-gray-900 font-bold dir-ltr">
                                        {{-- Arrival --}}
                                        <div class="text-center">
                                            <span class="text-sm text-gray-400 font-medium block mb-1">وصول</span>
                                            <span class="text-xl">{{ \Carbon\Carbon::parse($trip->trip_time)->addMinutes($trip->duration_minutes)->format('h:i') }}</span>
                                            <span class="text-xs text-gray-500 block mt-1">{{ \Carbon\Carbon::parse($trip->trip_time)->addMinutes($trip->duration_minutes)->format('A') }}</span>
                                        </div>

                                        {{-- Duration Line --}}
                                        <div class="flex flex-col items-center w-24">
                                            <span class="text-xs text-gray-400 mb-1 font-medium">{{ floor($trip->duration_minutes / 60) }} ساعات و {{ $trip->duration_minutes % 60 }} دقيقة</span>
                                            <div class="w-full h-[2px] bg-gray-200 relative">
                                                <div class="absolute w-2 h-2 bg-gray-300 rounded-full -top-[3px] left-0"></div>
                                                <div class="absolute w-2 h-2 bg-gray-300 rounded-full -top-[3px] right-0"></div>
                                            </div>
                                        </div>

                                        {{-- Departure --}}
                                        <div class="text-center">
                                            <span class="text-sm text-gray-400 font-medium block mb-1">مغادرة</span>
                                            <span class="text-xl">{{ \Carbon\Carbon::parse($trip->trip_time)->format('h:i') }}</span>
                                            <span class="text-xs text-gray-500 block mt-1">{{ \Carbon\Carbon::parse($trip->trip_time)->format('A') }}</span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Left Section: Price & Booking --}}
                                <div class="w-full lg:w-1/4 p-6 flex flex-col items-center justify-center bg-gray-50/30">
                                    <div class="text-center mb-4">
                                        <div class="flex items-center justify-center gap-1 mb-1">
                                            <span class="text-2xl font-extrabold text-blue-600">{{ number_format($trip->price) }}</span>
                                            <span class="text-sm font-bold text-gray-600">جنيه / اليوم</span> {{-- Replicating "Per Day" from image, though usually per trip --}}
                                        </div>
                                        <p class="text-xs font-bold text-gray-500">{{ $trip->available_seats_count }} مقعد متاح</p>
                                    </div>

                                    <form method="GET" action="{{ route('web.buses.select-seat', $trip->id) }}" class="w-full">
                                        <input type="hidden" name="number_of_passengers" value="{{ $searchData['number_of_passengers'] }}">
                                        <button type="submit" 
                                            class="w-full bg-safer-blue text-white py-3 rounded-full hover:bg-blue-700 active:scale-95 transition-all duration-200 font-bold shadow-lg shadow-blue-200 text-sm">
                                            احجز الآن
                                        </button>
                                    </form>
                                </div>

                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-20 bg-white rounded-[32px] shadow-sm border border-gray-100">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fa-solid fa-bus text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">لا توجد رحلات متاحة</h3>
                    <p class="text-gray-500 max-w-sm mx-auto mb-8 leading-relaxed">لم نجد رحلات تطابق بحثك في هذا التاريخ. جرب تغيير التاريخ او الوجهة.</p>
                    <a href="{{ route('web.buses.search') }}" 
                        class="inline-flex items-center gap-2 bg-gray-900 text-white px-8 py-3 rounded-full hover:bg-gray-800 transition font-bold text-sm">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        بحث جديد
                    </a>
                </div>
            @endif

        </div>
    </div>

    @include('partials.footer')

</body>
</html>