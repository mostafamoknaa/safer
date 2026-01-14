<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول | سافر</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f8f9fa;
        }

        .login-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.05);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .input-group {
            position: relative;
            margin-bottom: 20px;
        }

        .input-group i {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
        }

        .input-group .fa-eye,
        .input-group .fa-eye-slash {
            left: unset;
            right: 15px;
            cursor: pointer;
        }

        .input-field {
            width: 100%;
            padding: 12px 45px 12px 15px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            outline: none;
            transition: all 0.3s;
            text-align: right;
        }

        .input-field:focus {
            border-color: #2C67FF;
            box-shadow: 0 0 0 3px rgba(44, 103, 255, 0.1);
        }

        .btn-primary {
            background-color: #2C67FF;
            color: white;
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            font-weight: 700;
            transition: all 0.3s;
            margin-top: 20px;
        }

        .btn-primary:hover {
            background-color: #1A56DB;
            transform: translateY(-2px);
        }

        .google-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            width: 100%;
            padding: 12px;
            border: 1px solid #E5E7EB;
            border-radius: 12px;
            background: white;
            color: #374151;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s;
        }

        .google-btn:hover {
            background-color: #F9FAFB;
        }

        .divider {
            display: flex;
            align-items: center;
            text-align: center;
            margin: 25px 0;
            color: #9CA3AF;
            font-size: 14px;
        }

        .divider::before,
        .divider::after {
            content: '';
            flex: 1;
            border-bottom: 1px solid #E5E7EB;
        }

        .divider:not(:empty)::before {
            margin-left: .25em;
        }

        .divider:not(:empty)::after {
            margin-right: .25em;
        }

        .error-message {
            color: #EF4444;
            font-size: 13px;
            margin-top: 5px;
            text-align: right;
        }
    </style>
</head>

<body class="min-h-screen flex items-center justify-center p-4">

    <div class="login-card">
        <h1 class="text-2xl font-bold text-gray-900 text-center mb-8">سجّل دخولك لاكتشاف أفضل الوجهات السياحية</h1>

        <form id="loginForm" action="{{ route('login') }}" method="POST">
            @csrf
            
            <div class="space-y-1 mb-4">
                <label class="block text-sm font-semibold text-gray-700 text-right mb-1">البريد الإلكتروني</label>
                <div class="input-group">
                    <input type="email" name="email" value="{{ old('email') }}" class="input-field" placeholder="example@email.com" required>
                    <i class="fa-regular fa-envelope"></i>
                </div>
                <div id="emailError" class="error-message hidden"></div>
            </div>

            <div class="space-y-1 mb-2">
                <label class="block text-sm font-semibold text-gray-700 text-right mb-1">كلمة المرور</label>
                <div class="input-group">
                    <input type="password" id="password" name="password" class="input-field" placeholder="••••••••••••" required>
                    <i class="fa-solid fa-lock" style="left: 15px;"></i>
                    <i class="fa-regular fa-eye-slash" onclick="togglePassword()"></i>
                </div>
                <div id="passwordError" class="error-message hidden"></div>
            </div>
            
            <div id="generalError" class="error-message hidden mb-4 text-center bg-red-50 p-2 rounded"></div>

            <div class="flex items-center justify-between mb-6">
                <a href="#" class="text-sm text-blue-600 hover:underline">هل نسيت كلمة المرور؟</a>
                <label class="flex items-center gap-2 cursor-pointer">
                    <span class="text-sm text-gray-600">تذكرني</span>
                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                </label>
            </div>

            <button type="submit" class="btn-primary" id="submitBtn">تسجيل الدخول</button>

            <div class="divider">أو سجّل الدخول باستخدام</div>

            <button type="button" class="google-btn">
                <img src="https://www.gstatic.com/images/branding/product/1x/gsa_512dp.png" alt="Google" class="w-5 h-5">
                سجّل الدخول باستخدام
            </button>

            <p class="text-center text-gray-600 mt-8 text-sm">
                ليس لديك حساب؟ 
                <a href="{{ route('register') }}" class="text-blue-600 font-bold hover:underline">أنشئ حساباً</a>
            </p>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function togglePassword() {
            const password = document.getElementById('password');
            const icon = event.target;
            if (password.type === 'password') {
                password.type = 'text';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            } else {
                password.type = 'password';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            }
        }

        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const form = this;
            const submitBtn = document.getElementById('submitBtn');
            const formData = new FormData(form);
            
            // Clear previous errors
            document.querySelectorAll('.error-message').forEach(el => el.classList.add('hidden'));
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i> جارٍ التحميل...';

            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (response.ok && data.success) {
                    // Store token in localStorage
                    localStorage.setItem('auth_token', data.token);
                    
                    Swal.fire({
                        title: 'تم بنجاح!',
                        text: data.message,
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = data.redirect;
                    });
                } else {
                    throw data;
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'تسجيل الدخول';
                
                if (error.errors) {
                    if (error.errors.email) {
                        const emailErr = document.getElementById('emailError');
                        emailErr.innerText = error.errors.email[0];
                        emailErr.classList.remove('hidden');
                    }
                    if (error.errors.password) {
                        const passErr = document.getElementById('passwordError');
                        passErr.innerText = error.errors.password[0];
                        passErr.classList.remove('hidden');
                    }
                } else {
                    const genErr = document.getElementById('generalError');
                    genErr.innerText = error.message || 'حدث خطأ غير متوقع.';
                    genErr.classList.remove('hidden');
                }
            });
        });
    </script>
</body>

</html>