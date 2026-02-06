<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | الأنشطة والرحلات</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
        }

        .text-safer-blue {
            color: #2C67FF;
        }

        .bg-safer-blue {
            background-color: #2C67FF;
        }

        .event-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        .event-card {
            transition: all 0.3s ease;
        }
    </style>
</head>

<body class="bg-gray-50">
    @include('partials.navbar')

    <div class="container mx-auto px-4 py-12">
        <div class="mb-10 flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div>
                <h1 class="text-4xl font-bold text-gray-900 mb-2 border-r-4 border-blue-600 pr-4">الأنشطة والرحلات</h1>
                <p class="text-gray-600 pr-6">استمتع بأفضل الرحلات والأنشطة السياحية</p>
            </div>

            <!-- Tabs -->
            <div class="flex bg-white p-1 rounded-2xl shadow-sm border border-gray-100 w-fit">
                <button onclick="switchTab('trips')" id="btn-trips"
                    class="tab-btn active px-8 py-2.5 rounded-xl font-bold transition-all text-sm bg-blue-600 text-white shadow-lg shadow-blue-100">
                    الرحلات
                </button>
                <button onclick="switchTab('events')" id="btn-events"
                    class="tab-btn px-8 py-2.5 rounded-xl font-bold transition-all text-sm text-gray-500 hover:bg-gray-50 mr-1">
                    الفعاليات (Discovery)
                </button>
            </div>
        </div>

        <!-- Trips Tab Content -->
        <div id="tab-trips" class="tab-content">
            @if($trips->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($trips as $trip)
                        <div
                            class="event-card group relative bg-white rounded-[24px] overflow-hidden border border-gray-100 h-full flex flex-col">
                            <!-- Header / Badge -->
                            <div class="p-6 bg-safer-blue text-white flex justify-between items-center">
                                <div class="bg-white/20 backdrop-blur-md px-3 py-1.5 rounded-lg text-sm font-bold">
                                    {{ \Carbon\Carbon::parse($trip->trip_date)->format('d M Y') }}
                                </div>
                                <i class="fa-solid fa-bus text-xl opacity-50"></i>
                            </div>

                            <!-- Content -->
                            <div class="p-6 flex-1 flex flex-col text-right">
                                <div class="flex items-center gap-2 text-gray-500 text-xs mb-3">
                                    <i class="fa-solid fa-location-arrow text-blue-500"></i>
                                    <span>من: {{ $trip->departure_location_ar }}</span>
                                </div>

                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-1 group-hover:text-blue-600 transition">
                                    رحلة إلى {{ $trip->arrival_location_ar }}
                                </h3>

                                <div class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4">
                                    <div>
                                        <span
                                            class="text-2xl font-bold text-safer-blue">{{ number_format($trip->price) }}</span>
                                        <span class="text-xs text-gray-500">ج.م / فرد</span>
                                    </div>
                                    <a href="{{ route('web.services.trips.show', $trip->id) }}"
                                        class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:text-white hover:bg-[#2C67FF] transition">
                                        حجز الآن
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[24px] shadow-sm">
                    <p class="text-gray-500 text-xl font-semibold">لا يوجد رحلات متاحة حالياً</p>
                </div>
            @endif
        </div>

        <!-- Events Tab Content -->
        <div id="tab-events" class="tab-content hidden">
            @if($discoveryEvents->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($discoveryEvents as $event)
                        <div
                            class="event-card group relative bg-white rounded-[24px] overflow-hidden border border-gray-100 h-full flex flex-col">
                            <!-- Image Container -->
                            <div class="relative h-64 overflow-hidden">
                                @php
                                    $images = $event->activity_images;
                                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                    $imageUrl = $firstImage ? asset('storage/' . $firstImage) : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $event->name_ar }}"
                                    class="w-full h-full object-cover transform group-hover:scale-110 transition duration-700">

                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-90">
                                </div>

                                <div
                                    class="absolute top-4 right-4 bg-safer-blue text-white px-3 py-1.5 rounded-lg text-sm font-bold shadow-lg">
                                    {{ \Carbon\Carbon::parse($event->event_date)->format('d M') }}
                                </div>
                            </div>

                            <div class="p-6 flex-1 flex flex-col text-right">
                                <div class="flex items-center gap-2 text-gray-500 text-xs mb-3">
                                    <i class="fa-solid fa-location-dot text-blue-500"></i>
                                    <span>{{ $event->location_ar }}</span>
                                </div>

                                <h3
                                    class="text-xl font-bold text-gray-900 mb-3 line-clamp-1 group-hover:text-blue-600 transition">
                                    {{ $event->name_ar }}
                                </h3>

                                <div class="mt-auto flex items-center justify-between border-t border-gray-50 pt-4">
                                    <div>
                                        <span
                                            class="text-2xl font-bold text-safer-blue">{{ number_format($event->price_per_person) }}</span>
                                        <span class="text-xs text-gray-500">ج.م / فرد</span>
                                    </div>
                                    <a href="{{ route('web.events.show', $event->id) }}"
                                        class="bg-blue-50 text-blue-600 px-4 py-2 rounded-xl text-sm font-bold hover:text-white hover:bg-[#2C67FF] transition">
                                        التفاصيل
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-20 bg-white rounded-[24px] shadow-sm">
                    <p class="text-gray-500 text-xl font-semibold">لا يوجد فعاليات متاحة حالياً</p>
                </div>
            @endif
        </div>
    </div>

    @include('partials.footer')

    <script>
        function switchTab(tab) {
            document.querySelectorAll('.tab-content').forEach(content => content.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-100', 'active');
                btn.classList.add('text-gray-500', 'hover:bg-gray-50');
            });

            document.getElementById('tab-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tab);
            activeBtn.classList.add('bg-blue-600', 'text-white', 'shadow-lg', 'shadow-blue-100', 'active');
            activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');
        }
    </script>
</body>

</html>