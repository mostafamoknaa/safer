<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>سافر | {{ $car->name_ar }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        body {
            font-family: Cairo, sans-serif;
        }

        .gallery-main {
            height: 400px;
            border-radius: 20px;
            overflow: hidden;
        }

        .gallery-thumb {
            height: 100px;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
            border: 2px solid transparent;
            transition: all 0.3s;
        }

        .gallery-thumb.active {
            border-color: #2563eb;
        }

        .feature-box {
            background-color: #f8fafc;
            border-radius: 16px;
            padding: 1.5rem;
            border: 1px solid #f1f5f9;
            transition: all 0.3s ease;
        }

        .feature-box:hover {
            border-color: #3b82f6;
            background-color: #eff6ff;
            transform: translateY(-2px);
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Alerts Wrapper -->
    <div class="fixed top-6 left-1/2 -translate-x-1/2 z-[9999] w-[90%] max-w-md space-y-3">
        @if(session('success'))
            <div
                class="bg-green-100 border border-green-200 text-green-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce">
                <i class="fas fa-check-circle"></i>
                <p class="font-bold">{{ session('success') }}</p>
            </div>
        @endif
        @if(session('error'))
            <div
                class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-exclamation-circle"></i>
                <p class="font-bold">{{ session('error') }}</p>
            </div>
        @endif
        @if($errors->any())
            <div class="bg-red-100 border border-red-200 text-red-800 px-6 py-4 rounded-2xl shadow-xl">
                <div class="flex items-center gap-3 mb-2">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="font-bold">يرجى تصحيح الأخطاء التالية:</p>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @if(session('info'))
            <div
                class="bg-blue-100 border border-blue-200 text-blue-800 px-6 py-4 rounded-2xl shadow-xl flex items-center gap-3">
                <i class="fas fa-info-circle"></i>
                <p class="font-bold">{{ session('info') }}</p>
            </div>
        @endif
    </div>

    <!-- Navbar -->
    @include('partials.navbar')

    <main class="py-12 px-4 md:px-8 max-w-7xl mx-auto min-h-screen">
        <!-- Breadcrumb -->
        <nav class="flex mb-8 text-sm text-gray-500 font-medium">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600">الرئيسية</a>
            <span class="mx-2">/</span>
            <a href="{{ route('web.private_cars.index') }}" class="hover:text-blue-600">السيارات الخاصة</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">{{ $car->name_ar }}</span>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Right Column: Images & Details -->
            <div class="lg:col-span-2 space-y-8">
                <!-- Gallery -->
                <div class="bg-white rounded-[32px] p-6 shadow-sm border border-gray-100">
                    <div class="gallery-main mb-4 relative group">
                        @if($car->media->count() > 0)
                            <img id="mainImage" src="{{ $car->media->first()->file_url }}" alt="{{ $car->name_ar }}"
                                class="w-full h-full object-cover transition duration-500">
                        @else
                            <img id="mainImage" src="https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?w=800"
                                alt="{{ $car->name_ar }}" class="w-full h-full object-cover">
                        @endif
                    </div>
                    @if($car->media->count() > 1)
                        <div class="flex gap-4 overflow-x-auto pb-2">
                            @foreach($car->media as $media)
                                <div class="gallery-thumb w-24 flex-shrink-0 cursor-pointer {{ $loop->first ? 'active' : '' }}"
                                    onclick="changeImage('{{ $media->file_url }}', this)">
                                    <img src="{{ $media->file_url }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <!-- Technical Specs -->
                <div class="bg-white rounded-[32px] p-8 shadow-sm border border-gray-100 text-right">
                    <div class="flex items-center justify-between mb-8">
                        <h1 class="text-3xl font-black text-gray-900">{{ $car->name_ar }}</h1>
                        <div
                            class="px-4 py-2 bg-blue-50 text-blue-600 rounded-full font-bold text-sm border border-blue-100">
                            {{ $car->car_model }}
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-6 flex items-center gap-2">
                        <i class="fa-solid fa-car-side text-blue-500"></i>
                        المواصفات الفنية
                    </h3>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-8">
                        <!-- Power -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-bolt text-2xl text-yellow-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">قوة المحرك</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->power }} HP</p>
                        </div>

                        <!-- Acceleration -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-stopwatch text-2xl text-red-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">التسارع (0-100)</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->acceleration }} s</p>
                        </div>

                        <!-- Max Speed -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gauge-high text-2xl text-blue-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">السرعة القصوى</p>
                            <p class="font-black text-gray-800 dir-ltr text-lg">{{ $car->max_speed }} km/h</p>
                        </div>

                        <!-- Transmission -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gears text-2xl text-gray-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">ناقل الحركة</p>
                            <p class="font-black text-gray-800">
                                {{ $car->transmission == 'automatic' ? 'أوتوماتيك' : $car->transmission }}
                            </p>
                        </div>

                        <!-- Fuel Type -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-gas-pump text-2xl text-green-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">نوع الوقود</p>
                            <p class="font-black text-gray-800">
                                {{ $car->fuel_type == 'petrol' ? 'بنزين' : $car->fuel_type }}
                            </p>
                        </div>

                        <!-- Seats -->
                        <div class="feature-box text-center">
                            <i class="fa-solid fa-chair text-2xl text-purple-500 mb-2"></i>
                            <p class="text-xs text-gray-400 font-bold mb-1">عدد المقاعد</p>
                            <p class="font-black text-gray-800">{{ $car->seats_count }} مقاعد</p>
                        </div>
                    </div>

                    <h3 class="text-xl font-bold mb-4 flex items-center gap-2">
                        <i class="fa-solid fa-circle-info text-blue-500"></i>
                        تفاصيل إضافية
                    </h3>

                    <div
                        class="prose max-w-none text-gray-600 leading-relaxed bg-gray-50 p-6 rounded-2xl border border-gray-100">
                        <div class="mb-4">
                            <strong class="block text-gray-900 mb-1">الوصف بالعربية:</strong>
                            <p>{{ $car->notes_ar ?? 'لا يوجد وصف متاح.' }}</p>
                        </div>
                        <div class="pt-4 border-t border-gray-200">
                            <strong class="block text-gray-900 mb-1">English Description:</strong>
                            <p class="dir-ltr text-left">{{ $car->notes_en ?? 'No description available.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Left Column: Booking -->
            <div class="lg:col-span-1">
                <div
                    class="bg-white rounded-[32px] p-8 shadow-lg border border-gray-100 sticky top-28 text-right max-h-[calc(100vh-120px)] overflow-y-auto">
                    <div class="mb-8 pb-8 border-b border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <span class="text-sm text-gray-500 font-bold">طريقة الحجز</span>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <button type="button" onclick="setBookingType('days')" id="btnDaily"
                                class="py-3 rounded-xl border-2 border-blue-600 bg-blue-50 text-blue-600 font-bold transition">
                                <i class="fa-solid fa-calendar-day mb-1 block"></i>
                                يومي
                            </button>
                            <button type="button" onclick="setBookingType('hours')" id="btnHourly"
                                class="py-3 rounded-xl border-2 border-gray-100 text-gray-400 font-bold hover:border-blue-200 transition">
                                <i class="fa-solid fa-clock mb-1 block"></i>
                                بالساعة
                            </button>
                        </div>
                    </div>

                    <form id="carBookingForm" action="{{ route('web.services.private-car-request') }}" method="POST"
                        class="space-y-4">
                        @csrf
                        <input type="hidden" name="private_car_id" value="{{ $car->id }}">
                        <input type="hidden" name="booking_type" id="bookingTypeInput" value="days">

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ الاستلام</label>
                                <input type="date" name="pickup_date" id="pickupDate" required
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">وقت الاستلام</label>
                                <input type="time" name="pickup_time" id="pickupTime"
                                    class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                            </div>
                        </div>

                        <div id="returnDateContainer">
                            <label class="block text-sm font-bold text-gray-700 mb-2">تاريخ التسليم</label>
                            <input type="date" name="return_date" id="returnDate"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div id="durationContainer" class="hidden">
                            <label class="block text-sm font-bold text-gray-700 mb-2">عدد الساعات</label>
                            <input type="number" name="duration_hours" id="durationHours" value="1" min="1"
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">موقع الاستلام</label>
                            <input type="text" name="pickup_location" placeholder="أدخل مكان الاستلام" required
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-2">جهة الوصول</label>
                            <input type="text" name="destination" placeholder="أدخل مكان الوصول" required
                                class="w-full bg-gray-50 border-gray-200 rounded-xl p-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition">
                        </div>

                        <div class="py-6 text-center bg-blue-50 rounded-2xl border border-blue-100 mt-4">
                            <p class="text-xs text-blue-600 font-bold mb-1">إجمالي التكلفة المتوقعة</p>
                            <p id="carTotalPrice" class="text-3xl font-black text-blue-700 dir-ltr">
                                {{ number_format($car->price_per_day) }} <span class="text-sm">ج.م</span>
                            </p>
                        </div>

                        <button type="submit" id="carBookingBtn"
                            class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200 mt-6 block text-center transform hover:-translate-y-1">
                            تأكيد الحجز
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4 font-medium">سيتم مراجعة الطلب والتواصل معك</p>
                    </form>
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    @include('partials.footer')


    <script>
        function changeImage(src, element) {
            document.getElementById('mainImage').src = src;
            document.querySelectorAll('.gallery-thumb').forEach(el => el.classList.remove('active'));
            element.classList.add('active');
        }

        let bookingType = 'days';
        const pricePerDay = {{ $car->price_per_day }};
        const pricePerHour = {{ $car->price_per_hour ?? ($car->price_per_day / 24) }};

        function setBookingType(type) {
            bookingType = type;
            document.getElementById('bookingTypeInput').value = type;

            const btnDaily = document.getElementById('btnDaily');
            const btnHourly = document.getElementById('btnHourly');
            const returnDateContainer = document.getElementById('returnDateContainer');
            const durationContainer = document.getElementById('durationContainer');

            if (type === 'days') {
                btnDaily.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
                btnDaily.classList.remove('border-gray-100', 'text-gray-400');

                btnHourly.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
                btnHourly.classList.add('border-gray-100', 'text-gray-400');

                returnDateContainer.classList.remove('hidden');
                durationContainer.classList.add('hidden');
            } else {
                btnHourly.classList.add('border-blue-600', 'bg-blue-50', 'text-blue-600');
                btnHourly.classList.remove('border-gray-100', 'text-gray-400');

                btnDaily.classList.remove('border-blue-600', 'bg-blue-50', 'text-blue-600');
                btnDaily.classList.add('border-gray-100', 'text-gray-400');

                returnDateContainer.classList.add('hidden');
                durationContainer.classList.remove('hidden');
            }
            calculateCarPrice();
        }

        function calculateCarPrice() {
            const totalPriceEl = document.getElementById('carTotalPrice');
            let total = 0;

            if (bookingType === 'days') {
                const start = new Date(document.getElementById('pickupDate').value);
                const end = new Date(document.getElementById('returnDate').value);
                const nights = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)));

                if (start && end && end >= start) {
                    total = nights * pricePerDay;
                } else {
                    total = pricePerDay;
                }
            } else {
                const hours = parseInt(document.getElementById('durationHours').value) || 1;
                total = hours * pricePerHour;
            }

            totalPriceEl.innerHTML = `${total.toLocaleString()} <span class="text-sm">ج.م</span>`;
        }

        document.getElementById('pickupDate').addEventListener('change', calculateCarPrice);
        document.getElementById('returnDate').addEventListener('change', calculateCarPrice);
        document.getElementById('durationHours').addEventListener('input', calculateCarPrice);

        // Form submission feedback
        document.getElementById('carBookingForm').addEventListener('submit', function (e) {
            @guest
                e.preventDefault();
                Swal.fire({
                    title: 'تنبيه',
                    text: 'يجب عليك تسجيل الدخول أولاً لتتمكن من الحجز.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'تسجيل دخول',
                    cancelButtonText: 'إلغاء',
                    confirmButtonColor: '#2563eb'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = "{{ route('login') }}";
                    }
                });
            @else
                        const btn = document.getElementById('carBookingBtn');
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin ml-2"></i> جاري إرسال الطلب...';

                // Show a quick toast
                Swal.fire({
                    title: 'جاري الإرسال...',
                    text: 'يتم الآن معالجة طلبك، يرجى الانتظار.',
                    icon: 'info',
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    toast: true,
                    position: 'top-end'
                });
            @endguest
        });
    </script>
</body>

</html>