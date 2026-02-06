<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | حجوزاتي</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
            background-color: #F8F9FB;
        }

        .booking-card {
            transition: all 0.3s ease;
            border-radius: 24px;
            overflow: hidden;
            background: #fff;
        }

        .booking-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        }

        .tab-btn.active {
            background-color: #2563eb;
            color: white;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.2);
        }
    </style>
</head>

<body>
    @include('partials.navbar')

    <!-- Content -->
    <main class="py-12 px-4 md:px-8 max-w-5xl mx-auto min-h-screen">
        <h1 class="text-4xl font-bold text-gray-900 mb-8 text-right">حجوزاتي والطلبات</h1>

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

        <!-- Tabs Container -->
        <div class="flex flex-row-reverse mb-8 bg-white p-2 rounded-2xl shadow-sm border border-gray-100 w-fit ml-auto">
            <button onclick="switchTab('hotels')" id="btn-hotels" class="tab-btn active px-8 py-3 rounded-xl font-bold transition-all text-sm">
                الإقامات (الفنادق)
            </button>
            <button onclick="switchTab('services')" id="btn-services" class="tab-btn px-8 py-3 rounded-xl font-bold transition-all text-sm text-gray-500 hover:bg-gray-50 mr-2">
                خدمات (سيارات وأتوبيسات)
            </button>
            <button onclick="switchTab('events')" id="btn-events" class="tab-btn px-8 py-3 rounded-xl font-bold transition-all text-sm text-gray-500 hover:bg-gray-50 mr-2">
                الفعاليات (تذاكر)
            </button>
        </div>

        <!-- Hotels Tab Content -->
        <div id="tab-hotels" class="tab-content">
            @if($bookings->isEmpty())
                <div class="text-center py-20 bg-white rounded-[40px] shadow-sm">
                    <i class="fa-solid fa-calendar-xmark text-6xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 text-xl font-semibold">لا يوجد لديك حجوزات فنادق حالياً</p>
                    <a href="{{ route('web.home') }}" class="inline-block mt-6 bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition">ابدأ الحجز الآن</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($bookings as $booking)
                        @php $hotel = $booking->hotel; @endphp
                        <div class="booking-card p-4 flex flex-col md:flex-row gap-6 shadow-sm border border-gray-50">
                            <div class="w-full md:w-64 h-44 flex-shrink-0">
                                <img src="{{ $hotel->media->first() ? $hotel->media->first()->file_url : 'https://images.unsplash.com/photo-1551882547-ff40c63fe5fa?w=600' }}"
                                    alt="{{ $hotel->name_ar }}" class="w-full h-full object-cover rounded-2xl">
                            </div>
                            <div class="flex-1 flex flex-col justify-between py-2 text-right">
                                <div>
                                    <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
                                        <span class="bg-blue-100 text-blue-600 px-4 py-1.5 rounded-xl font-bold text-sm">الإقامات</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset {{ 
                                            $booking->status === 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 
                                            ($booking->status === 'approved' ? 'bg-green-50 text-green-700 ring-green-600/20' : 
                                            'bg-red-50 text-red-700 ring-red-600/20') 
                                        }}">
                                            @switch($booking->status)
                                                @case('pending') قيد المراجعة @break
                                                @case('approved') تم التأكيد @break
                                                @case('cancelled') ملغى @break
                                                @default {{ $booking->status }}
                                            @endswitch
                                        </span>
                                        <div class="flex gap-1 text-yellow-400">
                                            @for($i = 0; $i < 5; $i++)
                                                <i class="fa-solid fa-star text-xs {{ $i < round($hotel->rate ?? 5) ? '' : 'opacity-20' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $hotel->name_ar }}</h3>
                                    <div class="flex items-center justify-end gap-6 text-gray-500 text-sm">
                                        <div class="flex items-center gap-2">
                                            <span>{{ \Carbon\Carbon::parse($booking->check_in_date)->translatedFormat('d F Y') }}</span>
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $hotel->province ? $hotel->province->name_ar : '---' }}</span>
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                    </div>
                                </div>
                                    <div class="mt-6 md:mt-0 flex items-center justify-between md:justify-end md:gap-3">
                                        <div class="text-right ml-5">
                                            <p class="text-blue-600 font-bold text-2xl">{{ number_format($booking->total_price) }} ج.م</p>
                                        </div>
                                        <a href="{{ route('web.bookings.show', $booking) }}" class="px-6 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition">التفاصيل</a>
                                        @if($booking->status === 'pending')
                                            <button onclick="confirmCancel('{{ route('web.bookings.cancel', $booking) }}')" class="px-6 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-600 hover:text-white transition">إلغاء</button>
                                        @endif
                                    </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-12 flex justify-center">
                    {{ $bookings->links() }}
                </div>
            @endif
        </div>

        <!-- Services Tab Content -->
        <div id="tab-services" class="tab-content hidden">
            @if($requests->isEmpty())
                <div class="text-center py-20 bg-white rounded-[40px] shadow-sm">
                    <i class="fa-solid fa-car text-6xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 text-xl font-semibold">لا يوجد لديك طلبات خدمات حالياً</p>
                    <a href="{{ route('web.home') }}" class="inline-block mt-6 bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition">ابدأ الحجز الآن</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($requests as $request)
                        <div class="booking-card p-6 shadow-sm border border-gray-50 relative group">
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
                                            <p class="text-xs text-gray-400 mb-1">تاريخ الطلب</p>
                                            <p class="text-sm font-bold text-gray-700">{{ $request->start_date ? $request->start_date->format('Y-m-d') : '---' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">المسار</p>
                                            <p class="text-sm font-bold text-gray-700 truncate">
                                                {{ $request->departure_location_ar }} 
                                                <i class="fa-solid fa-arrow-left text-[10px] mx-1 text-gray-300"></i>
                                                {{ $request->arrival_location_ar }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-400 mb-1">نوع الحجز</p>
                                            <p class="text-sm font-bold text-gray-700">
                                                @if($request->booking_type === 'days')
                                                    يومي
                                                @elseif($request->booking_type === 'hours')
                                                    بالساعة ({{ $request->duration_hours }} ساعة)
                                                @else
                                                    ---
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
                                    <a href="{{ route('web.bookings.service_show', $request->id) }}" class="px-5 py-2.5 rounded-xl border border-gray-100 text-gray-600 text-sm font-bold hover:bg-gray-50 transition">التفاصيل</a>
                                    @if($request->status === 'pending')
                                        <button onclick="confirmCancel('{{ route('web.bookings.service_cancel', $request) }}')" class="px-5 py-2.5 rounded-xl bg-red-50 text-red-600 text-sm font-bold hover:bg-red-100 transition">إلغاء</button>
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

        <!-- Events Tab Content -->
        <div id="tab-events" class="tab-content hidden">
            @if($eventTickets->isEmpty())
                <div class="text-center py-20 bg-white rounded-[40px] shadow-sm">
                    <i class="fa-solid fa-ticket-simple text-6xl text-gray-200 mb-4"></i>
                    <p class="text-gray-500 text-xl font-semibold">لا يوجد لديك تذاكر فعاليات حالياً</p>
                    <a href="{{ route('web.events.index') }}" class="inline-block mt-6 bg-blue-600 text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition">اكتشف الفعاليات</a>
                </div>
            @else
                <div class="space-y-6">
                    @foreach($eventTickets as $ticket)
                        @php $event = $ticket->event; @endphp
                        <div class="booking-card p-4 flex flex-col md:flex-row gap-6 shadow-sm border border-gray-50">
                            <div class="w-full md:w-64 h-44 flex-shrink-0">
                                @php
                                    $images = $event->activity_images;
                                    $firstImage = is_array($images) && count($images) > 0 ? $images[0] : null;
                                    $imageUrl = $firstImage ? asset('storage/' . $firstImage) : 'https://images.unsplash.com/photo-1540575861501-7ad0582371f3?w=600';
                                @endphp
                                <img src="{{ $imageUrl }}" alt="{{ $event->name_ar }}" class="w-full h-full object-cover rounded-2xl">
                            </div>
                            <div class="flex-1 flex flex-col justify-between py-2 text-right">
                                <div>
                                    <div class="flex flex-wrap items-center justify-end gap-3 mb-4">
                                        <span class="bg-purple-100 text-purple-600 px-4 py-1.5 rounded-xl font-bold text-sm">الفعاليات</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-bold ring-1 ring-inset {{ 
                                            $ticket->status === 'pending' ? 'bg-yellow-50 text-yellow-700 ring-yellow-600/20' : 
                                            ($ticket->status === 'approved' || $ticket->status === 'active' ? 'bg-green-50 text-green-700 ring-green-600/20' : 
                                            'bg-red-50 text-red-700 ring-red-600/20') 
                                        }}">
                                            @switch($ticket->status)
                                                @case('pending') قيد المراجعة @break
                                                @case('approved') @case('active') تم التأكيد @break
                                                @case('cancelled') ملغى @break
                                                @default {{ $ticket->status }}
                                            @endswitch
                                        </span>
                                    </div>
                                    <h3 class="text-2xl font-bold text-gray-900 mb-3">{{ $event->name_ar }}</h3>
                                    <div class="flex items-center justify-end gap-6 text-gray-500 text-sm">
                                        <div class="flex items-center gap-2">
                                            <span>{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y') }}</span>
                                            <i class="fa-solid fa-calendar-days"></i>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $event->location_ar }}</span>
                                            <i class="fa-solid fa-location-dot"></i>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <span>{{ $ticket->tickets_count }} تذاكر</span>
                                            <i class="fa-solid fa-ticket"></i>
                                        </div>
                                    </div>
                                </div>
                                <div class="mt-6 md:mt-0 flex items-center justify-between md:justify-end md:gap-3">
                                    <div class="text-right ml-5">
                                        <p class="text-blue-600 font-bold text-2xl">{{ number_format($ticket->total_price) }} ج.م</p>
                                    </div>
                                    <a href="{{ route('web.bookings.event_ticket_show', $ticket->id) }}" class="px-6 py-2 bg-blue-50 text-blue-600 rounded-xl font-bold hover:bg-blue-600 hover:text-white transition">التفاصيل</a>
                                    @if($ticket->status === 'pending')
                                        <button onclick="confirmCancel('{{ route('web.bookings.event_ticket_cancel', $ticket) }}')" class="px-6 py-2 bg-red-50 text-red-600 rounded-xl font-bold hover:bg-red-600 hover:text-white transition">إلغاء</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </main>

    <form id="cancel-form" method="POST" class="hidden">
        @csrf
    </form>

    @include('partials.footer')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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

        function switchTab(tab) {
            // Hide all contents
            document.querySelectorAll('.tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            // Remove active style from all buttons
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove('active', 'bg-blue-600', 'text-white');
                btn.classList.add('text-gray-500', 'hover:bg-gray-50');
            });

            // Show current tab
            document.getElementById('tab-' + tab).classList.remove('hidden');
            const activeBtn = document.getElementById('btn-' + tab);
            activeBtn.classList.add('active', 'bg-blue-600', 'text-white');
            activeBtn.classList.remove('text-gray-500', 'hover:bg-gray-50');

            // Save preference in URL or session if needed (optional)
            const url = new URL(window.location);
            url.searchParams.set('tab', tab);
            window.history.pushState({}, '', url);
        }

        // Check URL for tab preference on load
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tab = urlParams.get('tab');
            if (tab && ['hotels', 'services', 'events'].includes(tab)) {
                switchTab(tab);
            }
        }
    </script>
</body>

</html>
