<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>سافر | الملف الشخصي</title>
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
    <nav class="bg-white px-8 py-4 shadow-sm flex items-center justify-between sticky top-0 z-50">
        <div class="flex items-center gap-2">
            <a href="{{ route('web.home') }}" class="w-10 h-10 flex items-center justify-center rounded-lg">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" alt="Safer Logo"
                    class="w-full h-full object-contain">
            </a>
        </div>
        <div class="hidden lg:flex items-center gap-8 text-gray-600 font-semibold">
            <a href="{{ route('web.home') }}" class="hover:text-blue-600 transition">الرئيسية</a>
            <a href="{{ route('web.hotels.index') }}" class="hover:text-blue-600 transition">الإقامات</a>
            <!-- Services Dropdown -->
            <div class="relative group">
                <button class="flex items-center gap-1 hover:text-blue-600 transition">
                    <span>الخدمات</span>
                    <i class="fa-solid fa-chevron-down text-xs"></i>
                </button>
                <div
                    class="absolute top-full right-0 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform translate-y-2 group-hover:translate-y-0 z-50 text-right">
                    <a href="{{ route('web.hotels.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-t-xl">
                        حجز فنادق
                    </a>
                    <a href="{{ route('web.private_cars.index') }}"
                        class="block px-4 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition rounded-b-xl">
                        حجز سيارات خاصة
                    </a>
                </div>
            </div>
            <a href="{{ route('web.events.index') }}" class="hover:text-blue-600 transition">الأنشطة</a>
            <a href="{{ route('web.contact') }}" class="hover:text-blue-600 transition">تواصل معنا</a>
        </div>
        <div class="flex items-center gap-4">
            @auth
                <div class="relative group">
                    <button
                        class="flex items-center gap-3 bg-gray-50 px-4 py-2 rounded-2xl border border-gray-100 hover:bg-gray-100 transition">
                        <span class="text-gray-900 font-bold">{{ auth()->user()->name }}</span>
                        <div
                            class="w-10 h-10 rounded-full bg-blue-600 flex items-center justify-center text-white overflow-hidden shadow-lg border-2 border-white">
                            @if(auth()->user()->image)
                                <img src="{{ auth()->user()->image }}" alt="User" class="w-full h-full object-cover">
                            @else
                                <i class="fa-solid fa-user"></i>
                            @endif
                        </div>
                    </button>
                    <!-- Dropdown Menu -->
                    <div
                       class="absolute left-0 mt-2 w-56 bg-white rounded-2xl shadow-2xl py-3 border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform translate-y-2 group-hover:translate-y-0 z-[100] text-right">
                        <a href="{{ route('web.profile.edit') }}" class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                            <span>الملف الشخصي</span>
                            <i class="fa-solid fa-user-pen text-sm"></i>
                        </a>
                        <a href="{{ route('web.favorites.index') }}"
                            class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition">
                            <span>المفضلة</span>
                            <i class="fa-solid fa-heart text-sm"></i>
                        </a>
                        <a href="{{ route('web.bookings.index') }}"
                            class="flex items-center justify-end gap-3 px-6 py-3 text-gray-700 hover:bg-gray-50 hover:text-blue-600 transition border-b border-gray-50">
                            <span>حجوزاتي</span>
                            <i class="fa-solid fa-calendar-check text-sm"></i>
                        </a>
                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                            @csrf
                            <button type="submit"
                                class="w-full flex items-center justify-end gap-3 px-6 py-3 text-red-500 hover:bg-red-50 transition">
                                <span>تسجيل خروج</span>
                                <i class="fa-solid fa-right-from-bracket text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <a href="{{ route('login') }}"
                    class="bg-blue-600 text-white px-8 py-2 rounded-full font-semibold hover:bg-blue-700 transition">
                    تسجيل دخول
                </a>
            @endauth
        </div>
    </nav>

    <!-- Content -->
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="container mx-auto px-4 max-w-3xl">
            <h1 class="text-3xl font-bold text-gray-800 mb-8 text-right">الملف الشخصي</h1>

            <div class="bg-white rounded-[32px] shadow-sm p-10 border border-gray-100 relative">
                
                <form action="{{ route('web.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Right Side: Avatar -->
                        <div class="flex flex-col items-center gap-4 text-center md:order-2">
                             <div class="relative group cursor-pointer" onclick="document.getElementById('imageInput').click()">
                                <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg">
                                    <img id="imagePreview" src="{{ $user->image ? $user->image : 'https://i.pravatar.cc/150?u='.$user->id }}" alt="Profile" class="w-full h-full object-cover">
                                </div>
                                <button type="button" class="absolute bottom-0 right-0 bg-blue-600 text-white w-8 h-8 rounded-full flex items-center justify-center shadow-md hover:bg-blue-700 transition">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </button>
                                <input type="file" name="image" id="imageInput" class="hidden" accept="image/*" onchange="previewImage(this)">
                            </div>
                            <div>
                                <h2 class="font-bold text-xl text-gray-900">{{ $user->name }}</h2>
                                <p class="text-gray-500 text-sm dir-ltr">{{ $user->email }}</p>
                            </div>
                        </div>

                        <!-- Left Side: Form Fields -->
                        <div class="flex-1 md:order-1 space-y-5 text-right">
                            
                            @if(session('success'))
                                <div class="bg-green-50 text-green-600 p-4 rounded-xl mb-4 text-sm font-bold border border-green-100 flex items-center gap-2">
                                    <i class="fa-solid fa-check-circle"></i>
                                    {{ session('success') }}
                                </div>
                            @endif

                             @if($errors->any())
                                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-4 text-sm font-bold border border-red-100">
                                    <ul class="list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">الاسم</label>
                                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">البريد الإلكتروني</label>
                                <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none text-right" dir="ltr">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">رقم الهاتف</label>
                                <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none text-right" dir="ltr">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">كلمة المرور</label>
                                <input type="password" name="password" placeholder="********" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none text-right" dir="ltr">
                            </div>

                            <div>
                                <label class="block text-gray-700 font-bold mb-2">تأكيد كلمة المرور</label>
                                <input type="password" name="password_confirmation" placeholder="********" class="w-full bg-gray-50 border-gray-100 rounded-xl px-4 py-3 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition outline-none text-right" dir="ltr">
                            </div>

                            <div class="pt-4">
                                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3.5 rounded-xl hover:bg-blue-700 transition shadow-lg shadow-blue-200">
                                    حفظ
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
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

    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('imagePreview').src = e.target.result;
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
