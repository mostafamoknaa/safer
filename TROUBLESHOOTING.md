# دليل حل مشكلة 403 Forbidden - خطوات مفصلة

## الخطوة 1: فحص ملف التشخيص

افتح المتصفح واذهب إلى:
```
http://your-domain.com/test.php
```

هذا الملف سيعرض لك معلومات مفصلة عن:
- مسارات الملفات
- الصلاحيات
- حالة الملفات المطلوبة
- إمكانية تحميل Laravel

## الخطوة 2: التحقق من إعدادات nginx

### أ) العثور على ملف الإعدادات

```bash
# البحث عن ملفات الإعدادات
sudo find /etc/nginx -name "*saferplus*" -o -name "*your-domain*"

# أو فحص جميع المواقع المتاحة
ls -la /etc/nginx/sites-available/
ls -la /etc/nginx/conf.d/
```

### ب) فتح ملف الإعدادات

```bash
sudo nano /etc/nginx/sites-available/saferplus.net
# أو
sudo nano /etc/nginx/conf.d/saferplus.net.conf
```

### ج) التأكد من الإعدادات الصحيحة

يجب أن يكون الملف كالتالي:

```nginx
server {
    listen 80;
    server_name saferplus.net www.saferplus.net;
    
    # مهم جداً: يجب أن يشير إلى public_html
    root /home/saferplus/htdocs/saferplus.net/public_html;
    
    index index.php index.html;
    
    charset utf-8;
    
    # Laravel routing - مهم جداً
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP processing
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        # أو قد يكون:
        # fastcgi_pass 127.0.0.1:9000;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    # منع الوصول للملفات المخفية
    location ~ /\. {
        deny all;
    }
    
    # منع الوصول لـ storage و bootstrap
    location ~ ^/(storage|bootstrap/cache) {
        deny all;
    }
}
```

### د) معرفة إصدار PHP-FPM

```bash
# البحث عن socket PHP-FPM
ls -la /var/run/php/

# أو فحص العمليات
ps aux | grep php-fpm

# أو فحص الإصدارات المثبتة
ls /etc/php/
```

## الخطوة 3: إصلاح الصلاحيات

```bash
cd /home/saferplus/htdocs/saferplus.net

# تعيين المالك (استبدل www-data بـ اسم مستخدم nginx إذا كان مختلف)
sudo chown -R $USER:www-data .

# تعيين الصلاحيات للمجلدات
sudo find . -type d -exec chmod 755 {} \;

# تعيين الصلاحيات للملفات
sudo find . -type f -exec chmod 644 {} \;

# صلاحيات خاصة لـ storage و bootstrap/cache
sudo chmod -R 775 storage
sudo chmod -R 775 bootstrap/cache

# صلاحيات خاصة لـ public_html
sudo chmod -R 755 public_html
```

## الخطوة 4: التحقق من PHP-FPM

```bash
# فحص حالة PHP-FPM
sudo systemctl status php8.2-fpm
# أو
sudo systemctl status php-fpm

# إعادة تشغيل PHP-FPM
sudo systemctl restart php8.2-fpm
```

## الخطوة 5: اختبار إعدادات nginx

```bash
# اختبار صحة الإعدادات
sudo nginx -t

# إذا كانت صحيحة، أعد تحميل nginx
sudo systemctl reload nginx

# أو إعادة التشغيل الكامل
sudo systemctl restart nginx
```

## الخطوة 6: فحص سجلات الأخطاء

```bash
# سجلات nginx
sudo tail -f /var/log/nginx/error.log

# سجلات PHP-FPM
sudo tail -f /var/log/php8.2-fpm.log
# أو
sudo journalctl -u php8.2-fpm -f

# سجلات Laravel
tail -f storage/logs/laravel.log
```

## الخطوة 7: التحقق من ملف .env

تأكد من وجود ملف `.env` في المجلد الرئيسي:

```bash
cd /home/saferplus/htdocs/saferplus.net
ls -la .env

# إذا لم يكن موجوداً، انسخ من .env.example
cp .env.example .env
php artisan key:generate
```

## الخطوة 8: اختبار الوصول

بعد تطبيق جميع الخطوات:

1. افتح المتصفح واذهب إلى: `http://your-domain.com`
2. افتح ملف التشخيص: `http://your-domain.com/test.php`
3. تحقق من سجلات الأخطاء إذا استمرت المشكلة

## المشاكل الشائعة وحلولها

### المشكلة: 403 Forbidden
**الحل:**
- تأكد من أن `root` في nginx يشير إلى `public_html`
- تأكد من الصلاحيات (755 للمجلدات، 644 للملفات)
- تأكد من أن nginx يمكنه قراءة الملفات

### المشكلة: 502 Bad Gateway
**الحل:**
- تأكد من أن PHP-FPM يعمل
- تأكد من أن `fastcgi_pass` في nginx صحيح
- تحقق من سجلات PHP-FPM

### المشكلة: 500 Internal Server Error
**الحل:**
- تحقق من سجلات Laravel: `storage/logs/laravel.log`
- تأكد من وجود ملف `.env`
- تأكد من صلاحيات `storage` و `bootstrap/cache`

## بعد حل المشكلة

**احذف ملف التشخيص:**
```bash
rm /home/saferplus/htdocs/saferplus.net/public_html/test.php
```


