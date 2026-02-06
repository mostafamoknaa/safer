<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | اختيار طريقة الدفع</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
            background-color: #F8F9FB;
        }

        .payment-card {
            border-radius: 32px;
            background: #fff;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.03);
            max-width: 500px;
            width: 100%;
        }

        .method-option {
            border: 2px solid #F1F5F9;
            border-radius: 20px;
            padding: 1.25rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }

        .method-option:hover {
            border-color: #2563eb;
            background-color: #F0F7FF;
        }

        .method-option.active {
            border-color: #2563eb;
            background-color: #F0F7FF;
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.1);
        }

        .method-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: #F8F9FB;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2563eb;
            font-size: 1.25rem;
            flex-shrink: 0;
        }

        .method-option.active .method-icon {
            background: #2563eb;
            color: #fff;
        }

        .radio-custom {
            width: 20px;
            height: 20px;
            border: 2px solid #CBD5E1;
            border-radius: 50%;
            margin-right: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s;
        }

        .method-option.active .radio-custom {
            border-color: #2563eb;
        }

        .method-option.active .radio-custom::after {
            content: '';
            width: 10px;
            height: 10px;
            background: #2563eb;
            border-radius: 50%;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen p-4">
    <div class="payment-card p-8 text-right">
        <div class="text-center mb-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">إتمام عملية الدفع</h1>
            <div class="text-gray-400 text-sm">المبلغ الإجمالي المستحق</div>
            <div class="text-4xl font-bold text-blue-600 mt-2">
                {{ number_format($amount) }} <span class="text-lg">ج.م</span>
            </div>
        </div>

        <form action="{{ route('web.payments.initiate', ['type' => $type, 'id' => $payable->id]) }}" method="GET"
            id="paymentForm">
            <!-- HIDDEN FIELDS handled by the GET route -->
            <input type="hidden" name="payment_method" value="online"> <!-- Always online as per request -->

            <p class="font-bold text-gray-900 mb-4 pr-1">اختر طريقة الدفع المناسبة:</p>

            <!-- Credit Card -->
            <label class="method-option active" onclick="selectMethod(this, 2)">
                <input type="radio" name="payment_method_id" value="2" class="hidden" checked>
                <div class="method-icon"><i class="fa-solid fa-credit-card"></i></div>
                <div class="flex-1">
                    <div class="font-bold text-gray-900">بطاقة ائتمان</div>
                    <div class="text-xs text-gray-400">Visa / MasterCard</div>
                </div>
                <div class="radio-custom"></div>
            </label>

            <!-- Fawry -->
            <label class="method-option" onclick="selectMethod(this, 3)">
                <input type="radio" name="payment_method_id" value="3" class="hidden">
                <div class="method-icon"><i class="fa-solid fa-receipt"></i></div>
                <div class="flex-1">
                    <div class="font-bold text-gray-900">فوري</div>
                    <div class="text-xs text-gray-400">تحصيل من ماكينات فوري</div>
                </div>
                <div class="radio-custom"></div>
            </label>

            <!-- Wallet -->
            <label class="method-option" onclick="selectMethod(this, 4)">
                <input type="radio" name="payment_method_id" value="4" class="hidden">
                <div class="method-icon"><i class="fa-solid fa-wallet"></i></div>
                <div class="flex-1">
                    <div class="font-bold text-gray-900">محفظة إلكترونية</div>
                    <div class="text-xs text-gray-400">Vodafone Cash / Orange / Etisalat</div>
                </div>
                <div class="radio-custom"></div>
            </label>

            <button type="submit"
                class="w-full bg-blue-600 text-white py-4 rounded-2xl font-bold hover:bg-blue-700 transition shadow-lg shadow-blue-100 mt-8 mb-4">
                ادفع الآن
            </button>

            <a href="{{ url()->previous() }}"
                class="block w-full text-center py-3 text-gray-400 font-bold hover:text-gray-600 transition">
                إلغاء
            </a>
        </form>

        <div
            class="mt-8 pt-8 border-t border-gray-100 flex flex-wrap items-center justify-center gap-4 opacity-50 grayscale hover:grayscale-0 transition-all">
            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" class="h-4" alt="Visa">
            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" class="h-6"
                alt="MasterCard">
            <img src="https://fawry.com/wp-content/uploads/2019/10/fawry-logo.png" class="h-4" alt="Fawry">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/c/c2/Vodafone_logo.svg/2560px-Vodafone_logo.svg.png"
                class="h-4" alt="Vodafone Cash">
        </div>
    </div>

    <script>
        function selectMethod(element, id) {
            // Remove active class from all
            document.querySelectorAll('.method-option').forEach(opt => opt.classList.remove('active'));
            // Add to current
            element.classList.add('active');
            // Check the radio
            element.querySelector('input').checked = true;
        }
    </script>
</body>

</html>