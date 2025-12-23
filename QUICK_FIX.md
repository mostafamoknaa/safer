# حل سريع لمشكلة 403 Forbidden

## الخطوات السريعة (نفذها بالترتيب):

### 1. فتح ملف إعدادات nginx
```bash
sudo nano /etc/nginx/sites-available/saferplus.net
```

### 2. التأكد من هذه السطور في الملف:

```nginx
root /home/saferplus/htdocs/saferplus.net/public_html;

location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

### 3. إصلاح الصلاحيات
```bash
cd /home/saferplus/htdocs/saferplus.net
sudo chown -R $USER:www-data .
sudo chmod -R 755 public_html
sudo chmod -R 775 storage bootstrap/cache
```

### 4. إعادة تشغيل الخدمات
```bash
sudo nginx -t
sudo systemctl reload nginx
sudo systemctl restart php8.2-fpm
```

### 5. اختبار الموقع
افتح في المتصفح: `http://your-domain.com/test.php`

إذا ظهرت صفحة التشخيص، فالمشكلة في Laravel.
إذا ظهر 403، فالمشكلة في nginx أو الصلاحيات.

### 6. فحص السجلات
```bash
sudo tail -f /var/log/nginx/error.log
```

---

## إذا استمرت المشكلة:

1. **تحقق من اسم socket PHP-FPM:**
```bash
ls -la /var/run/php/
```
ثم حدث `fastcgi_pass` في nginx بناءً على النتيجة.

2. **تحقق من أن nginx يمكنه الوصول للملفات:**
```bash
sudo -u www-data cat /home/saferplus/htdocs/saferplus.net/public_html/index.php
```

3. **تحقق من SELinux (إذا كان مفعلاً):**
```bash
getenforce
# إذا كان Enforcing، قد تحتاج إلى:
sudo setsebool -P httpd_read_user_content 1
```


