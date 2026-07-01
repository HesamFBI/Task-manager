<?php
// auth/register.php
require_once '../config/db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (!empty($username) && !empty($password)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, 'user')");
            $stmt->execute([$username, $hashed_password]);
            
            header("Location: login.php?registered=1");
            exit;
        } catch (PDOException $e) {
            if ($e->getCode() == 23000) {
                $error = "این نام کاربری قبلاً انتخاب شده است.";
            } else {
                $error = "خطا در ثبت دیتابیس: " . $e->getMessage();
            }
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
    <title>عضویت در HESAM</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { height: 100vh; background: #18191b; display: flex; justify-content: center; align-items: center; overflow: hidden; position: relative; padding: 20px; }
        
        /* افکت بک‌گراند متحرک دقیقا مثل داشبورد */
        .liquid-bg { position: absolute; width: 100%; height: 100%; top: 0; left: 0; z-index: -1; overflow: hidden; }
        .blob { position: absolute; border-radius: 50%; filter: blur(100px); mix-blend-mode: screen; opacity: 0.5; }
        .blob-1 { width: 500px; height: 500px; background: radial-gradient(circle, #2563eb, transparent); top: -10%; left: -10%; }
        .blob-2 { width: 500px; height: 500px; background: radial-gradient(circle, #3b82f6, transparent); bottom: -15%; right: -10%; }
        
        .auth-container { background: rgba(30, 31, 33, 0.4); backdrop-filter: blur(30px); -webkit-backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.05); width: 100%; max-width: 400px; border-radius: 28px; padding: 40px 30px; box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6); text-align: center; z-index: 1; }
        
        .logo-glow { width: 65px; height: 65px; background: linear-gradient(135deg, #3b82f6, #1d4ed8); border-radius: 20px; margin: 0 auto 15px; display: flex; justify-content: center; align-items: center; font-size: 1.4rem; font-weight: bold; color: white; box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3); }
        
        h2 { color: #fff; font-size: 1.4rem; font-weight: 500; margin-bottom: 5px; }
        p { color: #71717a; font-size: 0.85rem; margin-bottom: 25px; }
        
        .input-group { margin-bottom: 15px; text-align: right; }
        .input-label { color: #a1a1aa; font-size: 0.8rem; margin-bottom: 6px; display: block; padding-right: 4px; }
        input { width: 100%; background: #18191b; border: 1px solid rgba(255, 255, 255, 0.05); padding: 14px; border-radius: 14px; color: #fff; font-size: 0.95rem; outline: none; transition: 0.3s; }
        input:focus { border-color: #3b82f6; box-shadow: 0 0 10px rgba(59, 130, 246, 0.15); }
        
        button { width: 100%; padding: 14px; border-radius: 14px; border: none; background: #3b82f6; color: #fff; font-size: 0.95rem; font-weight: bold; cursor: pointer; margin-top: 15px; transition: 0.3s; box-shadow: 0 5px 15px rgba(59, 130, 246, 0.2); }
        button:hover { background: #2563eb; transform: translateY(-1px); }
        
        .error-box { background: rgba(248, 113, 113, 0.1); border: 1px solid rgba(248, 113, 113, 0.2); color: #f87171; font-size: 0.85rem; padding: 10px; border-radius: 12px; margin-bottom: 20px; text-align: right; }
        
        .switch-link { color: #94a3b8; text-decoration: none; font-size: 0.85rem; display: inline-block; margin-top: 25px; transition: 0.2s; }
        .switch-link:hover { color: #fff; }
        .switch-link span { color: #3b82f6; font-weight: bold; }
    </style>
</head>
<body>

    <div class="liquid-bg">
        <div class="blob blob-1"></div>
        <div class="blob blob-2"></div>
    </div>

    <div class="auth-container">
        <div class="logo-glow">H</div>
        <h2>عضویت جدید</h2>
        <p>به پلتفرم هوشمند HESAM خوش آمدید</p>
        
        <?php if($error): ?>
            <div class="error-box">⚠️ <?= $error ?></div>
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
            <button type="submit">ساخت حساب کاربری</button>
        </form>
        
        <a href="login.php" class="switch-link">قبلاً ثبت‌نام کرده‌اید؟ <span>ورود به حساب</span></a>
    </div>

</body>
</html>