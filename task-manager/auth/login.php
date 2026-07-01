<?php
// auth/login.php
require_once '../config/db.php';

if (isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$error = '';
$msg = isset($_GET['registered']) ? 'ثبت‌نام شما با موفقیت انجام شد! حالا وارد شوید.' : '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        try {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];

                if ($user['role'] === 'admin') {
                    header("Location: ../admin.php");
                } else {
                    header("Location: ../index.php");
                }
                exit;
            } else {
                $error = "نام کاربری یا رمز عبور اشتباه است.";
            }
        } catch (PDOException $e) {
            $error = "خطا در اتصال: " . $e->getMessage();
        }
    } else {
        $error = "لطفاً تمام فیلدها را پر کنید.";
    }
}
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ورود به HESAM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { height: 100vh; background: #18191b; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; padding: 20px; }
        
        /* افکت بک‌گراند متحرک مایع شبیه به داشبورد اصلی */
        .liquid-bg { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; overflow: hidden; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); mix-blend-mode: screen; opacity: 0.5; }
        .blob-1 { width: 500px; height: 500px; background: radial-gradient(circle, #2563eb, transparent); top: -10%; left: -10%; }
        .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #10b981, transparent); bottom: -15%; right: -10%; }
        
        .auth-container { background: rgba(30, 31, 33, 0.4); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.05); width: 100%; max-width: 400px; border-radius: 28px; padding: 40px 30px; box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6); text-align: center; z-index: 1; }
        
        .logo-glow { width: 65px; height: 65px; background: linear-gradient(135deg, #10b981, #059669); border-radius: 20px; margin: 0 auto 15px; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; font-weight: bold; color: white; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.3); }
        
        h2 { color: #fff; font-size: 1.4rem; font-weight: 500; margin-bottom: 5px; }
        p { color: #71717a; font-size: 0.85rem; margin-bottom: 25px; }
        
        .input-group { margin-bottom: 15px; text-align: right; }
        .input-label { color: #a1a1aa; font-size: 0.8rem; margin-bottom: 6px; display: block; padding-right: 4px; }
        input { width: 100%; background: #18191b; border: 1px solid rgba(255, 255, 255, 0.05); padding: 14px; border-radius: 14px; color: #fff; font-size: 0.95rem; outline: none; transition: 0.3s; }
        input:focus { border-color: #10b981; box-shadow: 0 0 10px rgba(16, 185, 129, 0.15); }
        
        button { width: 100%; padding: 14px; border-radius: 14px; border: none; background: #10b981; color: #fff; font-size: 0.95rem; font-weight: bold; cursor: pointer; margin-top: 15px; transition: 0.3s; box-shadow: 0 5px 15px rgba(16, 185, 129, 0.2); }
        button:hover { background: #059669; transform: translateY(-1px); }
        
        .error-box { background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); color: #f87171; font-size: 0.85rem; padding: 10px; border-radius: 12px; margin-bottom: 20px; text-align: right; }
        .success-box { background: rgba(16, 185, 129, 0.1); border: 1px solid rgba(16, 185, 129, 0.2); color: #10b981; font-size: 0.85rem; padding: 10px; border-radius: 12px; margin-bottom: 20px; text-align: right; }
        
        .switch-link { color: #94a3b8; text-decoration: none; font-size: 0.85rem; display: inline-block; margin-top: 25px; transition: 0.2s; }
        .switch-link:hover { color: #fff; }
        .switch-link span { color: #10b981; font-weight: bold; }
    </style>
</head>
<body>

    <div class="liquid-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="auth-container">
        <div class="logo-glow">H</div>
        <h2>ورود به حساب</h2>
        <p>خوش آمدید، لطفاً مشخصات خود را وارد کنید</p>
        
        <?php if($error): ?>
            <div class="error-box">⚠️ <?= $error ?></div>
        <?php endif; ?>

        <?php if($msg): ?>
            <div class="success-box">✓ <?= $msg ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="input-group">
                <label class="input-label">نام کاربری</label>
                <input type="text" name="username" placeholder="Username..." required>
            </div>
            <div class="input-group">
                <label class="input-label">رمز عبور</label>
                <input type="password" name="password" placeholder="Password..." required>
            </div>
            <button type="submit">ورود به پنل کاربری</button>
        </form>
        
        <a href="register.php" class="switch-link">هنوز ثبت‌نام نکرده‌اید؟ <span>عضویت رایگان</span></a>
    </div>

</body>
</html>