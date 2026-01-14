<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | اختيار المقاعد</title>
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

        .seat {
            @apply w-12 h-12 rounded-lg flex items-center justify-center font-bold text-sm cursor-pointer transition-all duration-200 relative;
        }

        .seat.available {
            @apply bg-gray-100 text-gray-700 hover:bg-blue-50 hover:text-blue-600 border-2 border-gray-200;
        }

        .seat.booked {
            @apply bg-gray-800 text-white cursor-not-allowed border-2 border-gray-800 opacity-50;
        }

        .seat.selected {
            @apply bg-safer-blue text-white border-2 border-safer-blue shadow-md;
        }

        /* Modal Transitions */
        .modal {
            transition: opacity 0.3s ease-in-out;
            opacity: 0;
            pointer-events: none;
        }
        
        .modal.active {
            opacity: 1;
            pointer-events: auto;
        }
        
        .modal-content {
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            transform: scale(0.95);
        }
        
        .modal.active .modal-content {
            transform: scale(1);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">

    @include('partials.navbar')

    <div class="pt-32 pb-20 relative overflow-hidden min-h-screen">
        {{-- Background Decoration --}}
        <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-blue-50/50 to-transparent -z-10 rounded-l-[100px] blur-3xl"></div>

        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                
                {{-- Trip Header --}}
                <div class="bg-white rounded-[32px] p-6 shadow-xl shadow-blue-100/50 border border-white mb-8 flex flex-col md:flex-row items-center justify-between gap-6 relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-32 h-full bg-safer-blue/5 skew-x-12 transform translate-x-10"></div>
                    
                    <div class="flex items-center gap-4 z-10">
                        <div class="w-14 h-14 bg-safer-blue rounded-2xl flex items-center justify-center text-white shadow-lg shadow-blue-200">
                            <i class="fa-solid fa-bus text-2xl"></i>
                        </div>
                        <div>
                            <h1 class="text-xl font-bold text-gray-900">اختيار المقاعد</h1>
                            <div class="flex items-center gap-2 text-gray-500 text-sm mt-1">
                                <span class="font-bold text-gray-900">{{ $trip->departure_location_ar }}</span>
                                <i class="fa-solid fa-arrow-left text-xs text-safer-blue"></i>
                                <span class="font-bold text-gray-900">{{ $trip->arrival_location_ar }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-6 z-10">
                        <div class="text-center px-6 py-2 bg-gray-50 rounded-2xl border border-gray-100">
                            <p class="text-xs text-gray-400 mb-1">عدد الركاب</p>
                            <p class="text-xl font-bold text-gray-900">{{ $numberOfPassengers }}</p>
                        </div>
                        <div class="text-center px-6 py-2 bg-blue-50 rounded-2xl border border-blue-100">
                            <p class="text-xs text-blue-500 mb-1">السعر للفرد</p>
                            <p class="text-xl font-bold text-safer-blue">{{ $trip->price }} <span class="text-xs">ج.م</span></p>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
                    
                    {{-- Left Side: Bus Map --}}
                    <div class="lg:col-span-8 order-2 lg:order-1">
                        <div class="bg-white rounded-[40px] shadow-xl shadow-gray-100/50 border border-gray-100 p-8 relative overflow-hidden">
                            
                            <div class="flex justify-between items-center mb-8 relative z-10">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">خريطة الحافلة</h3>
                                    <p class="text-gray-500 text-sm mt-1">اختر المقاعد المفضلة لديك</p>
                                </div>
                                <div class="flex gap-4 bg-gray-50 p-2 rounded-xl">
                                    <div class="flex items-center gap-2 px-3">
                                        <div class="w-3 h-3 bg-gray-800 rounded"></div>
                                        <span class="text-xs font-bold text-gray-600">محجوز</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 border-r border-gray-200">
                                        <div class="w-3 h-3 bg-safer-blue rounded"></div>
                                        <span class="text-xs font-bold text-gray-600">مختار</span>
                                    </div>
                                    <div class="flex items-center gap-2 px-3 border-r border-gray-200">
                                        <div class="w-3 h-3 bg-gray-100 border border-gray-200 rounded"></div>
                                        <span class="text-xs font-bold text-gray-600">متاح</span>
                                    </div>
                                </div>
                            </div>

                            <div class="relative bg-gray-50 rounded-[32px] p-8 border border-gray-100">
                                {{-- Driver Area --}}
                                <div class="absolute top-8 left-8">
                                    <i class="fa-solid fa-steering-wheel text-gray-300 text-3xl rotate-90"></i>
                                </div>
                                
                                <div class="flex justify-center my-8">
                                    <div class="w-full max-w-md">
                                        @php
                                            $totalSeats = $trip->bus->total_seats ?? 49;
                                            $bookedSeats = $trip->booked_seat_numbers ?? [];
                                        @endphp

                                        @if($totalSeats <= 14)
                                            {{-- Small Bus Layout (Numeric 1-14) --}}
                                            <div class="grid grid-cols-3 gap-4" dir="ltr">
                                                {{-- Driver side spacer --}}
                                                <div class="col-span-3 h-12"></div>
                                                
                                                @for($i = 1; $i <= $totalSeats; $i++)
                                                    @php $isBooked = in_array($i, $bookedSeats); @endphp
                                                    <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                        data-seat="{{ $i }}"
                                                        onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                        {{ $i }}
                                                    </div>
                                                @endfor
                                            </div>
                                        @else
                                            {{-- Large Bus Layout (Alphanumeric A1-A4...) --}}
                                            <div class="grid grid-cols-5 gap-y-4 gap-x-2 w-fit mx-auto" dir="ltr">
                                                
                                                {{-- Rows --}}
                                                @php
                                                    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'];
                                                    $seatsPerRow = 4;
                                                    $rowCount = ceil(($totalSeats - 5) / $seatsPerRow); // Reserve 5 seats for last row
                                                @endphp

                                                @for($r = 0; $r < $rowCount; $r++)
                                                    @php $rowLabel = $rows[$r]; @endphp
                                                    
                                                    {{-- Left Seats (1, 2) --}}
                                                    @for($c = 1; $c <= 2; $c++)
                                                        @php 
                                                            $seatId = $rowLabel . $c;
                                                            $isBooked = in_array($seatId, $bookedSeats);
                                                        @endphp
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ $seatId }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            {{ $seatId }}
                                                        </div>
                                                    @endfor

                                                    {{-- Aisle --}}
                                                    <div class="w-8 flex items-center justify-center text-xs text-gray-300 font-bold">{{ $rowLabel }}</div>

                                                    {{-- Right Seats (3, 4) --}}
                                                    @for($c = 3; $c <= 4; $c++)
                                                        @php 
                                                            $seatId = $rowLabel . $c;
                                                            $isBooked = in_array($seatId, $bookedSeats);
                                                        @endphp
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ $seatId }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            {{ $seatId }}
                                                        </div>
                                                    @endfor
                                                @endfor

                                                {{-- Last Row (5 seats) --}}
                                                <div class="col-span-5 pt-8 grid grid-cols-5 gap-2">
                                                    @for($k = 1; $k <= 5; $k++)
                                                        @php 
                                                            $lastRowLabel = end($rows); // Can be improved logic
                                                            $seatId = 'Z' . $k; // Using Z for back row for simplicity or custom logic
                                                            // Or continue numbering. Let's stick to a simple 5-seat back row
                                                            $isBooked = in_array($seatId, $bookedSeats);
                                                        @endphp
                                                        <!-- Placeholder for back seats logic, using consistent naming if needed -->
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ 'LR'.$k }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            {{ 'LR'.$k }}
                                                        </div>
                                                    @endfor
                                                </div>

                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Right Side: Booking Summary --}}
                    <div class="lg:col-span-4 order-1 lg:order-2">
                        <div class="bg-white rounded-[32px] shadow-xl shadow-blue-100/50 border border-gray-100 p-8 sticky top-32">
                            <h3 class="text-xl font-bold text-gray-900 mb-6">ملخص الحجز</h3>
                            
                            <div class="space-y-4 mb-8">
                                <div class="flex justify-between items-center p-4 bg-gray-50 rounded-2xl">
                                    <span class="text-gray-600 font-medium">عدد المقاعد المطلوبة</span>
                                    <span class="font-bold text-gray-900">{{ $numberOfPassengers }}</span>
                                </div>
                                <div class="flex justify-between items-center p-4 bg-blue-50 rounded-2xl border border-blue-100">
                                    <span class="text-blue-600 font-medium">المقاعد المختارة</span>
                                    <span class="font-bold text-safer-blue" id="selectedCountDisplay">0</span>
                                </div>
                                <div class="p-4 bg-gray-50 rounded-2xl">
                                    <span class="block text-gray-600 font-medium mb-2">أرقام المقاعد</span>
                                    <p class="text-gray-900 font-bold leading-relaxed break-words" id="selectedList">
                                        --
                                    </p>
                                </div>
                            </div>

                            <div class="pt-6 border-t border-gray-100">
                                <div class="flex justify-between items-end mb-6">
                                    <span class="text-gray-600">الإجمالي الكلي</span>
                                    <div class="text-right">
                                        <span class="text-3xl font-bold text-safer-blue">{{ number_format($trip->price * $numberOfPassengers) }}</span>
                                        <span class="text-sm text-gray-500">ج.م</span>
                                    </div>
                                </div>

                                <button id="paymentBtn" disabled onclick="openPaymentModal()"
                                    class="w-full bg-safer-blue text-white py-4 rounded-xl hover:bg-blue-700 transition-all font-bold text-lg shadow-lg shadow-blue-200 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed disabled:shadow-none mb-3">
                                    متابعة للدفع
                                </button>
                                <p class="text-xs text-center text-gray-400">بالنقر على متابعة، أنت توافق على الشروط والأحكام</p>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')

    {{-- Payment Modal --}}
    <div id="paymentModal" class="modal fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm">
        <div class="modal-content bg-white w-full max-w-md mx-4 rounded-[32px] p-8 shadow-2xl relative">
            <button onclick="closePaymentModal()" class="absolute top-6 right-6 text-gray-400 hover:text-gray-600 transition">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>

            <div class="text-center mb-8">
                <h3 class="text-2xl font-bold text-gray-900 mb-2">حدد طريقة الدفع</h3>
                <p class="text-gray-500">اختر وسيلة الدفع المناسبة لك لإتمام الحجز</p>
            </div>

            <form method="POST" action="{{ route('web.buses.confirm-seat', $trip->id) }}" id="seatForm" class="space-y-4">
                @csrf
                <input type="hidden" name="number_of_passengers" value="{{ $numberOfPassengers }}">
                <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                
                {{-- Payment Options --}}
                <label class="group relative flex items-center justify-between p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-safer-blue hover:bg-blue-50/30 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment_method" value="mastercard" class="peer sr-only" required>
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-safer-blue peer-checked:bg-safer-blue transition-colors relative flex items-center justify-center">
                            <i class="fa-solid fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100"></i>
                        </div>
                        <span class="font-bold text-gray-700 group-hover:text-gray-900">ماستركارد (Mastercard)</span>
                    </div>
                    {{-- Mastercard Icon Placeholder --}}
                    <div class="w-10 h-6 bg-red-500/20 rounded-md flex gap-[-5px]">
                        <div class="w-6 h-full bg-[#EB001B] rounded-full opacity-80"></div>
                        <div class="w-6 h-full bg-[#F79E1B] rounded-full opacity-80 -ml-3"></div>
                    </div>
                </label>

                <label class="group relative flex items-center justify-between p-4 rounded-2xl border-2 border-gray-100 cursor-pointer hover:border-safer-blue hover:bg-blue-50/30 transition-all duration-200">
                    <div class="flex items-center gap-4">
                        <input type="radio" name="payment_method" value="visa" class="peer sr-only">
                        <div class="w-6 h-6 rounded-full border-2 border-gray-300 peer-checked:border-safer-blue peer-checked:bg-safer-blue transition-colors relative flex items-center justify-center">
                            <i class="fa-solid fa-check text-white text-[10px] opacity-0 peer-checked:opacity-100"></i>
                        </div>
                        <span class="font-bold text-gray-700 group-hover:text-gray-900">فيزا (Visa)</span>
                    </div>
                    <div class="text-blue-800 text-2xl font-bold font-serif italic">VISA</div>
                </label>
                
                <button type="submit" class="w-full bg-safer-blue text-white py-4 rounded-xl hover:bg-blue-700 transition font-bold text-lg mt-8 shadow-lg shadow-blue-200">
                    تأكيد الدفع
                </button>
            </form>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxSeats = {{ $numberOfPassengers }};
        const modal = document.getElementById('paymentModal');

        function toggleSeat(element, isBooked) {
            if (isBooked) return;

            const seatNumber = element.getAttribute('data-seat');

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                element.classList.add('available');
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            } else {
                if (selectedSeats.length >= maxSeats) {
                    // Shake animation or toast could define better UX
                    alert('لقد اخترت العدد المطلوب من المقاعد');
                    return;
                }
                element.classList.remove('available');
                element.classList.add('selected');
                selectedSeats.push(seatNumber);
            }

            updateDisplay();
        }

        function updateDisplay() {
            // Update Counts
            document.getElementById('selectedCountDisplay').textContent = selectedSeats.length;
            
            // Update List
            const listEl = document.getElementById('selectedList');
            if (selectedSeats.length > 0) {
                listEl.textContent = selectedSeats.join(' , ');
                listEl.classList.remove('text-gray-400');
                listEl.classList.add('text-gray-900');
            } else {
                listEl.textContent = '--';
                listEl.classList.add('text-gray-400');
                listEl.classList.remove('text-gray-900');
            }

            // Update Input
            document.getElementById('selectedSeatsInput').value = JSON.stringify(selectedSeats);
            
            // Enable/Disable Button
            const btn = document.getElementById('paymentBtn');
            if (selectedSeats.length === maxSeats) {
                btn.disabled = false;
                btn.classList.add('transform', 'hover:-translate-y-1');
            } else {
                btn.disabled = true;
                btn.classList.remove('transform', 'hover:-translate-y-1');
            }
        }

        function openPaymentModal() {
            modal.classList.add('active');
        }

        function closePaymentModal() {
            modal.classList.remove('active');
        }

        // Close modal on click outside
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closePaymentModal();
            }
        });
    </script>

</body>
</html>