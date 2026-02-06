<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .content {
            padding: 40px 30px;
            text-align: center;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 8px;
            margin: 30px 0;
            padding: 20px;
            background-color: #f8f9fa;
            border-radius: 8px;
            display: inline-block;
        }
        .message {
            color: #666;
            font-size: 16px;
            line-height: 1.6;
            margin: 20px 0;
        }
        .footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #999;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>رمز التحقق</h1>
        </div>
        <div class="content">
            <p class="message">مرحباً،</p>
            <p class="message">رمز التحقق الخاص بك هو:</p>
            <div class="otp-code">{{ $otp }}</div>
            <p class="message">هذا الرمز صالح لمدة 10 دقائق فقط.</p>
            <p class="message">إذا لم تطلب هذا الرمز، يرجى تجاهل هذه الرسالة.</p>
        </div>
        <div class="footer">
            <p>شكراً لاستخدامك خدماتنا</p>
            <p>© {{ date('Y') }} SaferPlus. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
