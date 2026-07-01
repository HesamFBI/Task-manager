<?php
// auth/logout.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// پاک کردن تمام متغیرهای سشن
$_SESSION = array();

// از بین بردن کوکی سشن در مرورگر
if (ini_get("session_use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

// نابود کردن کامل سشن
session_destroy();

// هدایت مستقیم به صفحه لاگین چون هر دو در یک پوشه (auth) هستند
header("Location: login.php");
exit;