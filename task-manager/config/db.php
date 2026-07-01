<?php
// config/db.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$host = 'localhost';
$dbname = 'hesam_db'; // نام دیتابیس جدید شما که در phpMyAdmin ساختید
$username = 'root';
$password = ''; // در زمپ به صورت پیش‌فرض خالی است

try {
    // اتصال با استفاده از PDO برای هماهنگی کامل با فایل process.php
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("خطا در اتصال به دیتابیس: " . $e->getMessage());
}