<!-- Footer -->
<footer class="bg-[#0A1124] text-white pt-20 pb-10 px-8">
    <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 text-right">
        <!-- Brand & Description -->
        <div class="lg:col-span-1">
            <div class="flex items-center justify-start gap-2 mb-6">
                <img src="{{ asset('9f0a5356f37b3a4ffa50fe9cf73267fbc8015c0d.png') }}" class="w-20 h-20 bg-white">
            </div>
            <p class="text-gray-400 text-sm leading-relaxed mb-8">
                موقع سياحي متكامل يساعدك على التخطيط لرحلتك بسهولة، من حجز المواصلات والإقامة إلى اكتشاف الأنشطة
                المميزة في أفضل الوجهات.
            </p>
            <div class="flex justify-end gap-3">
                <a href="#" class="w-32 transition hover:opacity-80">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/3/3c/Download_on_the_App_Store_Badge.svg"
                        alt="App Store" class="w-full">
                </a>
                <a href="#" class="w-32 transition hover:opacity-80">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg"
                        alt="Google Play" class="w-full">
                </a>
            </div>
        </div>

        <!-- Quick Links -->
        <div>
            <h3 class="font-bold text-xl mb-6">روابط سريعة</h3>
            <ul class="space-y-4 text-gray-400">
                <li><a href="{{ route('web.home') }}" class="hover:text-white transition">الرئيسية</a></li>
                <li><a href="{{ route('web.hotels.index') }}" class="hover:text-white transition">الإقامات</a></li>
                <li><a href="{{ route('web.buses.search') }}" class="hover:text-white transition">الخدمات</a></li>
                <li><a href="{{ route('web.events.index') }}" class="hover:text-white transition">الانشطة</a></li>
                <li><a href="{{ route('web.contact') }}" class="hover:text-white transition">اتصل بنا</a></li>
            </ul>
        </div>

        <!-- Contact Info -->
        <div>
            <h3 class="font-bold text-xl mb-6">تواصل معنا</h3>
            <ul class="space-y-4 text-gray-400">
                <li class="flex items-center justify-end gap-3">
                    <span>مصر</span>
                    <i class="fa-solid fa-location-dot text-sm"></i>
                </li>
                <li class="flex items-center justify-end gap-3">
                    <span>support@alfosafr.com</span>
                    <i class="fa-solid fa-envelope text-sm"></i>
                </li>
                <li class="flex items-center justify-end gap-3">
                    <span dir="ltr">+20 120 495 750</span>
                    <i class="fa-solid fa-phone text-sm"></i>
                </li>
            </ul>
        </div>

        <!-- Empty column for spacing or other content -->
        <div></div>
    </div>

    <div class="max-w-7xl mx-auto mt-20 pt-8 border-t border-gray-800 text-center text-gray-500 text-sm">
        <p>© 2025 سافر. جميع الحقوق محفوظة.</p>
    </div>
</footer>