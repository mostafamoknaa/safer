# API Documentation

## Base URL
```
http://your-domain.com/api
```

## Authentication
يستخدم API نظام Laravel Sanctum للمصادقة. بعد تسجيل الدخول، ستحصل على token يجب إرساله في header كل طلب محمي.

### Headers المطلوبة للطلبات المحمية:
```
Authorization: Bearer {token}
Accept: application/json
Content-Type: application/json
```

---

## Endpoints

### 1. إنشاء حساب جديد (Register)

**POST** `/api/register`

#### Request Body:
```json
{
    "name": "أحمد محمد",
    "email": "ahmed@example.com",
    "phone": "01234567890",
    "password": "password123"
}
```

#### Response (Success - 201):
```json
{
    "success": true,
    "message": "تم إنشاء الحساب بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "ahmed@example.com",
            "phone": "01234567890"
        },
        "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

#### Response (Error - 422):
```json
{
    "success": false,
    "message": "فشل التحقق من البيانات",
    "errors": {
        "email": ["البريد الإلكتروني مستخدم بالفعل"],
        "phone": ["رقم الهاتف مستخدم بالفعل"]
    }
}
```

---

### 2. تسجيل الدخول (Login)

**POST** `/api/login`

#### Request Body:
```json
{
    "email": "ahmed@example.com",
    "password": "password123"
}
```

#### Response (Success - 200):
```json
{
    "success": true,
    "message": "تم تسجيل الدخول بنجاح",
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "ahmed@example.com",
            "phone": "01234567890"
        },
        "token": "2|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
        "token_type": "Bearer"
    }
}
```

#### Response (Error - 401):
```json
{
    "success": false,
    "message": "بيانات الدخول غير صحيحة"
}
```

---

### 3. الحصول على بيانات المستخدم الحالي (Me)

**GET** `/api/me`

**محمي - يتطلب token**

#### Headers:
```
Authorization: Bearer {token}
```

#### Response (Success - 200):
```json
{
    "success": true,
    "data": {
        "user": {
            "id": 1,
            "name": "أحمد محمد",
            "email": "ahmed@example.com",
            "phone": "01234567890"
        }
    }
}
```

---

### 4. تسجيل الخروج (Logout)

**POST** `/api/logout`

**محمي - يتطلب token**

#### Headers:
```
Authorization: Bearer {token}
```

#### Response (Success - 200):
```json
{
    "success": true,
    "message": "تم تسجيل الخروج بنجاح"
}
```

---

## أمثلة الاستخدام

### JavaScript (Fetch API)

#### تسجيل الدخول:
```javascript
const response = await fetch('http://your-domain.com/api/login', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json'
    },
    body: JSON.stringify({
        email: 'ahmed@example.com',
        password: 'password123'
    })
});

const data = await response.json();
const token = data.data.token;

// حفظ token
localStorage.setItem('token', token);
```

#### طلب محمي:
```javascript
const token = localStorage.getItem('token');

const response = await fetch('http://your-domain.com/api/me', {
    method: 'GET',
    headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
    }
});

const data = await response.json();
console.log(data.data.user);
```

### cURL

#### تسجيل الدخول:
```bash
curl -X POST http://your-domain.com/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "ahmed@example.com",
    "password": "password123"
  }'
```

#### طلب محمي:
```bash
curl -X GET http://your-domain.com/api/me \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "Accept: application/json"
```

---

## ملاحظات مهمة

1. جميع الـ endpoints ترجع JSON
2. يجب إرسال header `Accept: application/json` في جميع الطلبات
3. Token صالح حتى يتم تسجيل الخروج أو حذفه
4. عند تسجيل الدخول، يتم حذف جميع الـ tokens السابقة وإنشاء token جديد
5. الحد الأدنى لطول كلمة المرور: 8 أحرف
6. البريد الإلكتروني ورقم الهاتف يجب أن يكونا فريدين

