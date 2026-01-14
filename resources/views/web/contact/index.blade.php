<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | تواصل معنا</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: Cairo, sans-serif;
        }
    </style>
</head>

<body class="bg-gray-50">
    <!-- Navbar -->
    @include('partials.navbar')

    <!-- Content -->
    <div class="bg-white min-h-screen">
        <div class="container mx-auto px-4 py-8 lg:py-16">
            <h1 class="text-4xl font-bold text-gray-900 mb-4 text-right">تواصل معنا</h1>
            <p class="text-gray-500 mb-12 text-right">نحن هنا لمساعدتك في أي وقت – لا تتردد في التواصل معنا.</p>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-24 items-center">
                <div class="md:order-1 flex flex-col items-center justify-center text-center lg:text-right">
                    <div class="mb-10 w-full max-w-md mx-auto">
                        <img src="{{ asset("contact_us.png") }}"
                            class="w-full object-contain mix-blend-multiply" alt="Contact Us">
                    </div>

                    <div
                        class="space-y-4 w-full max-w-xs mx-auto lg:mx-0 lg:mr-auto lg:max-w-none text-gray-600 dir-ltr text-right">
                        <a href="mailto:info@travelwebsite.com"
                            class="flex items-center justify-end gap-3 hover:text-blue-600 transition">
                            <span class="font-medium">info@travelwebsite.com</span>
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-regular fa-envelope"></i>
                            </div>
                        </a>
                        <div class="flex items-center justify-end gap-3 text-right">
                            <span class="font-medium">القاهرة، مصر</span>
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                        </div>
                        <a href="tel:+441234567890"
                            class="flex items-center justify-end gap-3 hover:text-blue-600 transition">
                            <span class="font-medium text-right direction-ltr">+44 123 456 7890</span>
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center">
                                <i class="fa-solid fa-mobile-screen"></i>
                            </div>
                        </a>

                        <!-- Social Media -->
                        <div class="flex justify-end gap-3 pt-4">
                            <a href="#"
                                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition">
                                <i class="fa-brands fa-linkedin-in"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                            <a href="#"
                                class="w-8 h-8 rounded-full bg-gray-100 text-gray-600 hover:bg-blue-600 hover:text-white flex items-center justify-center transition">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        </div>
                    </div>
                </div> 
                <div class="md:order-2">
                    @if(session('success'))
                        <div
                            class="bg-green-50 text-green-600 p-4 rounded-xl mb-6 text-sm font-bold border border-green-100 flex items-center gap-2">
                            <i class="fa-solid fa-check-circle"></i>
                            {{ session('success') }}
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm font-bold border border-red-100">
                            <ul class="list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('web.contact.store') }}" method="POST" class="space-y-5">
                        @csrf

                        <div>
                            <input type="text" name="name" placeholder="الاسم" value="{{ old('name') }}"
                                class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none shadow-sm placeholder-gray-400">
                        </div>

                        <div>
                            <input type="email" name="email" placeholder="البريد الإلكتروني" value="{{ old('email') }}"
                                class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none shadow-sm placeholder-gray-400">
                        </div>

                        <div>
                            <input type="text" name="phone" placeholder="الهاتف" value="{{ old('phone') }}"
                                class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none shadow-sm placeholder-gray-400">
                        </div>

                        <div>
                            <textarea name="message" rows="5" placeholder="الرسالة"
                                class="w-full bg-white border border-gray-100 rounded-xl px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none shadow-sm placeholder-gray-400">{{ old('message') }}</textarea>
                        </div>

                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                            إرسال
                        </button>
                    </form>
                </div>
                              
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-[#0A1124] text-white pt-20 pb-10 px-8">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-right">
            <div>
                <div class="flex items-center justify-start gap-2 mb-6">
                    <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" class="w-20 h-20 bg-white">
                </div>
                <p class="text-gray-400 text-sm leading-relaxed mb-8">موقع سياحي متكامل يساعدك على التخطيط لرحلتك
                    بسهولة...</p>
                <div class="flex justify-end gap-3">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                        class="w-32">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                        class="w-32">
                </div>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">روابط سريعة</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li>الرئيسية</li>
                    <li>الإقامات</li>
                    <li>الخدمات</li>
                    <li>الانشطة</li>
                    <li>اتصل بنا</li>
                </ul>
            </div>
            <div>
                <h3 class="font-bold text-lg mb-6">تواصل معنا</h3>
                <ul class="space-y-4 text-gray-400 text-sm">
                    <li class="flex items-center justify-end gap-3"><span>مصر</span><i
                            class="fa-solid fa-location-dot"></i></li>
                    <li class="flex items-center justify-end gap-3"><span>support@alfosafr.com</span><i
                            class="fa-solid fa-envelope"></i></li>
                    <li class="flex items-center justify-end gap-3"><span dir="ltr">+20 120 495 750</span><i
                            class="fa-solid fa-phone"></i></li>
                </ul>
            </div>
            <div></div>
        </div>
        <div class="max-w-7xl mx-auto mt-16 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
            <p>© 2025 سافر. جميع الحقوق محفوظة.</p>
        </div>
    </footer>
</body>

</html>