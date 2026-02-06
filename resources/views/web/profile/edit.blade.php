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
    @include('partials.navbar')


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
                                <div id="avatarContainer" class="w-24 h-24 rounded-full overflow-hidden border-4 border-white shadow-lg bg-gray-100 flex items-center justify-center">
                                    @if($user->image)
                                        <img id="imagePreview" src="{{ $user->image }}" alt="Profile" class="w-full h-full object-cover">
                                    @else
                                        <div id="imagePlaceholder" class="w-full h-full flex items-center justify-center bg-blue-50 text-blue-600">
                                            <i class="fa-solid fa-user text-3xl"></i>
                                        </div>
                                        <img id="imagePreview" src="" alt="Profile" class="w-full h-full object-cover hidden">
                                    @endif
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
    @include('partials.footer')


    <script>
        function previewImage(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('imagePreview');
                    const placeholder = document.getElementById('imagePlaceholder');
                    
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) {
                        placeholder.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
</body>

</html>
