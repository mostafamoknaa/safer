# فحص إعدادات nginx - خطوات مهمة

## المشكلة: 404 Not Found لملف test.php

هذا يعني أن nginx لا يجد الملف أو أن Laravel routing يلتقط الطلب قبل الوصول إليه.

## الحلول:

### 1. تحديث إعدادات nginx (الأهم)

افتح ملف إعدادات nginx:
```bash
sudo nano /etc/nginx/sites-available/saferplus.net
```

يجب أن يحتوي على:

```nginx
server {
    listen 80;
    server_name saferplus.net www.saferplus.net;
    
    # مهم جداً: يجب أن يشير إلى public_html
    root /home/saferplus/htdocs/saferplus.net/public_html;
    
    index index.php index.html;
    
    charset utf-8;
    
    # السماح بالوصول المباشر لملفات الاختبار
    location ~ ^/(test|info)\.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
    
    # Laravel routing
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # PHP processing للباقي
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }
    
    # منع الوصول للملفات المخفية
    location ~ /\. {
        deny all;
    }
}
```

### 2. التحقق من document root

```bash
# فحص ما يشير إليه nginx فعلياً
sudo nginx -T | grep -A 10 "server_name saferplus.net"
```

### 3. اختبار الإعدادات

```bash
sudo nginx -t
```

إذا كانت صحيحة:
```bash
sudo systemctl reload nginx
```

### 4. اختبار الملفات

بعد التحديث، جرب:
- `https://saferplus.net/info.php` - يجب أن يعرض phpinfo
- `https://saferplus.net/test.php` - يجب أن يعرض صفحة التشخيص

### 5. إذا استمر 404

تحقق من:
```bash
# فحص أن الملف موجود
ls -la /home/saferplus/htdocs/saferplus.net/public_html/test.php

# فحص الصلاحيات
sudo -u www-data cat /home/saferplus/htdocs/saferplus.net/public_html/test.php

# فحص سجلات nginx
sudo tail -f /var/log/nginx/error.log
```

### 6. معرفة إصدار PHP-FPM

```bash
# البحث عن socket
ls -la /var/run/php/

# أو
ps aux | grep php-fpm | head -1
```

ثم استبدل `php8.2-fpm.sock` في إعدادات nginx بالإصدار الصحيح.


