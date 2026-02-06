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
            width: 3.25rem;
            height: 3.75rem;
            border-radius: 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            border: 2px solid transparent;
        }

        .seat.available {
            background-color: #f8fafc;
            color: #94a3b8;
            border-color: #e2e8f0;
        }

        .seat.available:hover {
            background-color: #f0f7ff;
            color: #2C67FF;
            border-color: #2C67FF;
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 10px 15px -3px rgba(44, 103, 255, 0.1);
        }

        .seat.booked {
            background-color: #1e293b;
            color: #64748b;
            cursor: not-allowed;
            opacity: 0.4;
            border-color: #1e293b;
        }

        .seat.selected {
            background-color: #2C67FF;
            color: #ffffff;
            border-color: #2C67FF;
            box-shadow: 0 10px 20px -5px rgba(44, 103, 255, 0.4);
            animation: seat-select 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        @keyframes seat-select {
            0% { transform: scale(0.9); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        .seat.limit-reached:not(.selected) {
            opacity: 0.3;
            cursor: not-allowed;
            filter: grayscale(0.5);
            transform: scale(0.95);
        }

        .seat i {
            font-size: 1.25rem;
            margin-bottom: 2px;
        }

        .seat span {
            font-size: 0.65rem;
            font-weight: 800;
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
                                <div class="flex gap-4 bg-gray-50 p-3 rounded-2xl border border-gray-100">
                                    <div class="flex items-center gap-2.5 px-3">
                                        <div class="w-8 h-8 bg-gray-800/40 rounded-lg flex items-center justify-center text-gray-500">
                                            <i class="fa-solid fa-chair text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600">محجوز</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 px-3 border-r border-gray-200">
                                        <div class="w-8 h-8 bg-safer-blue rounded-lg flex items-center justify-center text-white shadow-md shadow-blue-200">
                                            <i class="fa-solid fa-chair text-sm"></i>
                                        </div>
                                        <span class="text-xs font-bold text-gray-600">مختار</span>
                                    </div>
                                    <div class="flex items-center gap-2.5 px-3 border-r border-gray-200">
                                        <div class="w-8 h-8 bg-white border-2 border-gray-200 rounded-lg flex items-center justify-center text-gray-400">
                                            <i class="fa-solid fa-chair text-sm"></i>
                                        </div>
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
                                                        <i class="fa-solid fa-chair"></i>
                                                        <span>{{ $i }}</span>
                                                    </div>
                                                @endfor
                                            </div>
                                        @else
                                            {{-- Large Bus Layout (Alphanumeric A1-A4...) --}}
                                            <div class="grid grid-cols-5 gap-y-4 gap-x-2 w-fit mx-auto" dir="ltr">
                                                
                                                {{-- Rows --}}
                                                @php
                                                    $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P'];
                                                    $seatsPerRow = 4;
                                                    $rowCount = ceil(($totalSeats - 5) / $seatsPerRow);
                                                    $seatIndex = 1;
                                                @endphp

                                                @for($r = 0; $r < $rowCount; $r++)
                                                    @php $rowLabel = $rows[$r] ?? '?'; @endphp
                                                    
                                                    {{-- Left Seats (1, 2) --}}
                                                    @for($c = 1; $c <= 2; $c++)
                                                        @php 
                                                            $seatId = $rowLabel . $c;
                                                            $isBooked = in_array($seatId, $bookedSeats) || in_array((string)$seatIndex, $bookedSeats);
                                                        @endphp
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ $seatId }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            <i class="fa-solid fa-chair"></i>
                                                            <span>{{ $seatId }}</span>
                                                        </div>
                                                        @php $seatIndex++; @endphp
                                                    @endfor
 
                                                    {{-- Aisle --}}
                                                    <div class="w-8 flex items-center justify-center text-xs text-gray-300 font-bold">{{ $rowLabel }}</div>
 
                                                    {{-- Right Seats (3, 4) --}}
                                                    @for($c = 3; $c <= 4; $c++)
                                                        @php 
                                                            $seatId = $rowLabel . $c;
                                                            $isBooked = in_array($seatId, $bookedSeats) || in_array((string)$seatIndex, $bookedSeats);
                                                        @endphp
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ $seatId }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            <i class="fa-solid fa-chair"></i>
                                                            <span>{{ $seatId }}</span>
                                                        </div>
                                                        @php $seatIndex++; @endphp
                                                    @endfor
                                                @endfor

                                                <div class="col-span-5 pt-8 grid grid-cols-5 gap-2">
                                                    @for($k = 1; $k <= 5; $k++)
                                                        @php 
                                                            $seatId = 'LR' . $k;
                                                            $isBooked = in_array($seatId, $bookedSeats) || in_array((string)$seatIndex, $bookedSeats);
                                                        @endphp
                                                        <div class="seat {{ $isBooked ? 'booked' : 'available' }}"
                                                            data-seat="{{ $seatId }}"
                                                            onclick="toggleSeat(this, {{ $isBooked ? 'true' : 'false' }})">
                                                            <i class="fa-solid fa-chair"></i>
                                                            <span>{{ $seatId }}</span>
                                                        </div>
                                                        @php $seatIndex++; @endphp
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
                <div class="w-16 h-16 bg-blue-50 text-safer-blue rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fa-solid fa-circle-info text-2xl"></i>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">تأكيد طلب الحجز</h3>
                <p class="text-gray-500">سيتم إرسال طلب حجز المقاعد للمراجعة. يمكنك الدفع فور تأكيد الحجز من قبل الإدارة.</p>
            </div>

            <form method="POST" action="{{ route('web.buses.confirm-seat', $trip->id) }}" id="seatForm" class="space-y-4">
                @csrf
                <input type="hidden" name="number_of_passengers" value="{{ $numberOfPassengers }}">
                <input type="hidden" name="selected_seats" id="selectedSeatsInput">
                
                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100 mb-6">
                    <div class="flex justify-between items-center mb-4">
                        <span class="text-gray-500">عدد المقاعد</span>
                        <span class="font-bold text-gray-900">{{ $numberOfPassengers }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-gray-500">إجمالي المبلغ</span>
                        <span class="font-bold text-safer-blue text-xl">{{ number_format($trip->price * $numberOfPassengers) }} ج.م</span>
                    </div>
                </div>
                
                <button type="submit" class="w-full bg-safer-blue text-white py-4 rounded-xl hover:bg-blue-700 transition font-bold text-lg shadow-lg shadow-blue-200">
                    تأكيد وإرسال الطلب
                </button>
            </form>
        </div>
    </div>

    <script>
        let selectedSeats = [];
        const maxSeats = {{ $numberOfPassengers }};
        const modal = document.getElementById('paymentModal');

        function toggleSeat(element, isBooked) {
            if (isBooked || element.classList.contains('limit-reached') && !element.classList.contains('selected')) return;

            const seatNumber = element.getAttribute('data-seat');

            if (element.classList.contains('selected')) {
                element.classList.remove('selected');
                element.classList.add('available');
                selectedSeats = selectedSeats.filter(s => s !== seatNumber);
            } else {
                if (selectedSeats.length >= maxSeats) return;
                
                element.classList.remove('available');
                element.classList.add('selected');
                selectedSeats.push(seatNumber);
            }

            updateDisplay();
        }

        function updateDisplay() {
            // Update Counts
            document.getElementById('selectedCountDisplay').textContent = selectedSeats.length;
            
            // Handle Limit Reached State
            const allAvailableSeats = document.querySelectorAll('.seat.available');
            if (selectedSeats.length >= maxSeats) {
                allAvailableSeats.forEach(s => s.classList.add('limit-reached'));
            } else {
                allAvailableSeats.forEach(s => s.classList.remove('limit-reached'));
            }
            
            // Update List
            const listEl = document.getElementById('selectedList');
            if (selectedSeats.length > 0) {
                listEl.innerHTML = selectedSeats.map(s => `<span class="inline-block bg-blue-50 text-safer-blue px-3 py-1 rounded-lg border border-blue-100 mr-2 mb-2 font-extrabold text-sm">${s}</span>`).join('');
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