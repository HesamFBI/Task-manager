<?php
// includes/header.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// بازگشت به پوشه قبل و ورود به پوشه config
require_once __DIR__ . '/../config/db.php';

// بررسی وضعیت لاگین کاربر
if (!isset($_SESSION['user_id'])) {
    header("Location: auth/login.php");
    exit;
}

// جلوگیری از دسترسی ادمین به پنل کاربران عادی
if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit;
}
?>