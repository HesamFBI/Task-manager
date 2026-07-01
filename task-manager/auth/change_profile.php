<?php
// auth/change_profile.php
require_once __DIR__ . '/../config/db.php'; // اتصال به دیتابیس با PDO

// بررسی اینکه کاربر حتماً وارد حساب خود شده باشد
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$error_msg = '';
$success_msg = '';

// دریافت اطلاعات فعلی کاربر برای نمایش در فرم
$stmt_user = $pdo->prepare("SELECT username FROM users WHERE id = :id");
$stmt_user->execute([':id' => $user_id]);
$current_user = $stmt_user->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_username = trim($_POST['username']);
    $new_password = $_POST['password'];

    if (empty($new_username)) {
        $error_msg = "نام کاربری نمی‌تواند خالی باشد.";
    } else {
        try {
            // ۱. بروزرسانی نام کاربری
            $stmt_update = $pdo->prepare("UPDATE users SET username = :username WHERE id = :id");
            $stmt_update->execute([':username' => $new_username, ':id' => $user_id]);
            $_SESSION['username'] = $new_username; // بروزرسانی نام در سشن

            // ۲. اگر رمز عبور جدید وارد شده بود، آن را هش و بروزرسانی کن
            if (!empty($new_password)) {
                if (strlen($new_password) < 4) {
                    $error_msg = "رمز عبور باید حداقل ۴ رقم باشد.";
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt_pass = $pdo->prepare("UPDATE users SET password = :password WHERE id = :id");
                    $stmt_pass->execute([':password' => $hashed_password, ':id' => $user_id]);
                }
            }

            if (empty($error_msg)) {
                $success_msg = "مشخصات حساب کاربری با موفقیت بروزرسانی شد.";
                // بروزرسانی اطلاعات برای نمایش مجدد در فرم
                $current_user['username'] = $new_username;
            }

        } catch (PDOException $e) {
            $error_msg = "این نام کاربری قبلاً توسط شخص دیگری انتخاب شده است.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HESAM | ویرایش حساب کاربری</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { height: 100vh; background: #18191b; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; padding: 20px; }
        
        /* افکت پس‌زمینه مایع شبیه به صفحه اصلی */
        .liquid-bg { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; overflow: hidden; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); mix-blend-mode: screen; opacity: 0.5; }
        .blob-1 { width: 400px; height: 400px; background: radial-gradient(circle, #2563eb, transparent); top: -5%; left: -5%; }
        
        .profile-card { background: rgba(30, 31, 33, 0.6); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.06); border-radius: 24px; width: 100%; max-width: 420px; padding: 35px 25px; box-shadow: 0 30px 60px rgba(0,0,0,0.6); text-align: center; }
        
        h2 { color: #fff; font-size: 1.25rem; font-weight: 500; margin-bottom: 25px; }
        
        .form-group { margin-bottom: 20px; text-align: right; }
        .form-label { color: #a1a1aa; font-size: 0.8rem; display: block; margin-bottom: 8px; padding-right: 4px; }
        .form-input { background: #121314; border: 1px solid rgba(255, 255, 255, 0.05); padding: 14px; border-radius: 14px; color: #fff; font-size: 0.9rem; outline: none; width: 100%; transition: 0.3s; text-align: right; }
        .form-input:focus { border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.2); }
        
        .msg { font-size: 0.8rem; padding: 12px; border-radius: 12px; margin-bottom: 20px; text-align: right; }
        .msg-error { background: rgba(239, 68, 68, 0.1); color: #ef4444; border: 1px solid rgba(239, 68, 68, 0.15); }
        .msg-success { background: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.15); }
        
        .btn-submit { background: #3b82f6; color: #fff; border: none; width: 100%; padding: 14px; border-radius: 14px; font-size: 0.9rem; font-weight: bold; cursor: pointer; transition: 0.3s; box-shadow: 0 5px 15px rgba(59, 130, 246, 0.3); margin-top: 10px; }
        .btn-submit:hover { background: #2563eb; }
        
        .back-link { display: inline-block; margin-top: 20px; color: #71717a; text-decoration: none; font-size: 0.8rem; transition: 0.2s; }
        .back-link:hover { color: #fff; }
    </style>
</head>
<body>

    <div class="liquid-bg">
        <div class="blob blob-1"></div>
    </div>

    <div class="profile-card">
        <h2>⚙️ تنظیمات حساب کاربری</h2>
        
        <?php if (!empty($error_msg)): ?>
            <div class="msg msg-error">⚠️ <?= htmlspecialchars($error_msg) ?></div>
        <?php endif; ?>
        
        <?php if (!empty($success_msg)): ?>
            <div class="msg msg-success">✅ <?= htmlspecialchars($success_msg) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="form-group">
                <label class="form-label">نام کاربری فعلی / جدید</label>
                <input type="text" name="username" class="form-input" value="<?= htmlspecialchars($current_user['username'] ?? '') ?>" required autocomplete="off">
            </div>
            
            <div class="form-group">
                <label class="form-label">رمز عبور جدید (اگر تمایل به تغییر دارید)</label>
                <input type="password" name="password" class="form-input" placeholder="••••••••" autocomplete="new-password">
                <span style="font-size: 0.7rem; color: #52525b; margin-top: 5px; display: block; padding-right: 4px;">اگر مایل به تغییر رمز نیستید، این کادر را خالی بگذارید.</span>
            </div>
            
            <button type="submit" class="btn-submit">ذخیره تغییرات</button>
        </form>
        
        <a href="../index.php" class="back-link">بازگشت به داشبورد اصلی ←</a>
    </div>

</body>
</html>