# دليل حل مشكلة 403 Forbidden مع nginx

## المشكلة
بعد تغيير اسم مجلد `public` إلى `public_html`، يظهر خطأ 403 Forbidden.

## الحلول المطلوبة

### 1. تحديث إعدادات nginx

يجب أن يشير `root` في إعدادات nginx إلى مجلد `public_html` وليس المجلد الرئيسي للمشروع.

#### مثال لإعدادات nginx الصحيحة:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /home/saferplus/htdocs/saferplus.net/public_html;  # مهم: يجب أن يشير إلى public_html
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

#### خطوات التطبيق:

1. افتح ملف إعدادات nginx للموقع:
   ```bash
   sudo nano /etc/nginx/sites-available/saferplus.net
   # أو
   sudo nano /etc/nginx/conf.d/saferplus.net.conf
   ```

2. تأكد من أن `root` يشير إلى `public_html`:
   ```nginx
   root /home/saferplus/htdocs/saferplus.net/public_html;
   ```

3. تأكد من وجود `try_files` في location /:
   ```nginx
   location / {
       try_files $uri $uri/ /index.php?$query_string;
   }
   ```

4. اختبر الإعدادات:
   ```bash
   sudo nginx -t
   ```

5. أعد تحميل nginx:
   ```bash
   sudo systemctl reload nginx
   ```

### 2. التحقق من الصلاحيات

تأكد من أن nginx يمكنه قراءة الملفات:

```bash
# تعيين المالك الصحيح
sudo chown -R www-data:www-data /home/saferplus/htdocs/saferplus.net

# أو إذا كان المستخدم الحالي هو المالك:
sudo chown -R $USER:www-data /home/saferplus/htdocs/saferplus.net

# تعيين الصلاحيات الصحيحة
sudo chmod -R 755 /home/saferplus/htdocs/saferplus.net
sudo chmod -R 775 /home/saferplus/htdocs/saferplus.net/storage
sudo chmod -R 775 /home/saferplus/htdocs/saferplus.net/bootstrap/cache
```

### 3. التحقق من PHP-FPM

تأكد من أن PHP-FPM يعمل:

```bash
sudo systemctl status php8.2-fpm  # أو الإصدار المستخدم لديك
sudo systemctl restart php8.2-fpm
```

### 4. التحقق من ملف index.php

تأكد من وجود ملف `index.php` في `public_html` وأنه يحتوي على:

```php
<?php
define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
```

### 5. فحص سجلات الأخطاء

راجع سجلات nginx لتحديد المشكلة بدقة:

```bash
sudo tail -f /var/log/nginx/error.log
```

## ملاحظات مهمة

- يجب أن يشير `root` في nginx دائماً إلى مجلد `public_html` (أو `public` في الحالة الافتراضية)
- لا تشير `root` أبداً إلى المجلد الرئيسي للمشروع
- تأكد من أن `try_files` موجود في إعدادات nginx
- تأكد من أن PHP-FPM يعمل بشكل صحيح


