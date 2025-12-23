<?php
/**
 * ملف تشخيصي للتحقق من إعدادات السيرفر
 * احذف هذا الملف بعد حل المشكلة
 */

echo "<h1>تشخيص السيرفر</h1>";

echo "<h2>1. معلومات PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Server Software: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'غير معروف') . "<br>";
echo "Document Root: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'غير معروف') . "<br>";
echo "Script Filename: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'غير معروف') . "<br>";

echo "<h2>2. المسارات</h2>";
$basePath = dirname(__DIR__);
echo "Base Path: " . $basePath . "<br>";
echo "Public HTML Path: " . __DIR__ . "<br>";
echo "Storage Path: " . $basePath . "/storage<br>";
echo "Bootstrap Path: " . $basePath . "/bootstrap<br>";
echo "Vendor Path: " . $basePath . "/vendor<br>";

echo "<h2>3. فحص الملفات</h2>";
$checks = [
    'index.php' => __DIR__ . '/index.php',
    'vendor/autoload.php' => $basePath . '/vendor/autoload.php',
    'bootstrap/app.php' => $basePath . '/bootstrap/app.php',
    'storage directory' => $basePath . '/storage',
    'bootstrap/cache directory' => $basePath . '/bootstrap/cache',
];

foreach ($checks as $name => $path) {
    $exists = file_exists($path);
    $readable = $exists ? is_readable($path) : false;
    $writable = $exists ? is_writable($path) : false;
    
    echo "$name: " . ($exists ? "✓ موجود" : "✗ غير موجود");
    if ($exists) {
        echo " | قابل للقراءة: " . ($readable ? "✓" : "✗");
        echo " | قابل للكتابة: " . ($writable ? "✓" : "✗");
    }
    echo "<br>";
}

echo "<h2>4. الصلاحيات</h2>";
foreach ($checks as $name => $path) {
    if (file_exists($path)) {
        $perms = fileperms($path);
        echo "$name: " . substr(sprintf('%o', $perms), -4) . "<br>";
    }
}

echo "<h2>5. فحص PHP Extensions</h2>";
$required = ['pdo', 'mbstring', 'openssl', 'tokenizer', 'json', 'ctype', 'fileinfo'];
foreach ($required as $ext) {
    echo "$ext: " . (extension_loaded($ext) ? "✓ مثبت" : "✗ غير مثبت") . "<br>";
}

echo "<h2>6. محاولة تحميل Laravel</h2>";
try {
    require $basePath . '/vendor/autoload.php';
    echo "✓ تم تحميل Composer autoload بنجاح<br>";
    
    $app = require $basePath . '/bootstrap/app.php';
    echo "✓ تم تحميل Laravel Application بنجاح<br>";
} catch (Exception $e) {
    echo "✗ خطأ في تحميل Laravel: " . $e->getMessage() . "<br>";
}


