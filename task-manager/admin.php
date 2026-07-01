<?php
// admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// لود کردن فایل دیتابیس
require_once 'config/db.php';

// بررسی سطح دسترسی کاربر (فقط کاربران با نقش ادمین اجازه ورود دارند)
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: auth/login.php");
    exit;
}

// بخش فیلتر و جستجو (چالش‌های درخواستی استاد)
$search = $_GET['search'] ?? '';
$filter_status = $_GET['status'] ?? '';

// پایه گذاری کوئری بر اساس PDO
$query = "SELECT tasks.*, users.username FROM tasks JOIN users ON tasks.user_id = users.id WHERE 1=1";
$params = [];

if (!empty($search)) {
    $query .= " AND tasks.title LIKE :search";
    $params[':search'] = '%' . $search . '%';
}
if (!empty($filter_status)) {
    $query .= " AND tasks.status = :status";
    $params[':status'] = $filter_status;
}
$query .= " ORDER BY tasks.id DESC";

// اجرای کوئری با استفاده از PDO Prepared Statements امن
$stmt = $pdo->prepare($query);
$stmt->execute($params);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fa" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>داشبورد مدیریت | HESAM</title>
    <style>
        /* تم هماهنگ با کل سایت */
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { min-height: 100vh; background: #090d16; color: white; padding: 40px; display: flex; justify-content: center; }
        .admin-container { background: rgba(255, 255, 255, 0.04); backdrop-filter: blur(30px); border: 1px solid rgba(255, 255, 255, 0.12); border-radius: 24px; padding: 30px; width: 100%; max-width: 1100px; box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5); }
        .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 15px; margin-bottom: 20px; }
        .filter-bar { display: flex; gap: 15px; margin-bottom: 20px; }
        .glass-input { background: rgba(255, 255, 255, 0.06); border: 1px solid rgba(255, 255, 255, 0.1); padding: 10px; border-radius: 10px; color: white; outline: none; }
        .btn { background: #3b82f6; border: none; padding: 10px 20px; color: white; border-radius: 10px; cursor: pointer; text-decoration: none; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { padding: 12px; text-align: right; border-bottom: 1px solid rgba(255,255,255,0.08); }
        th { background: rgba(255,255,255,0.05); color: #67e8f9; }
        .status { padding: 4px 8px; border-radius: 6px; font-size: 0.8rem; }
        .status-in { background: rgba(59, 130, 246, 0.2); color: #3b82f6; }
        .status-cp { background: rgba(52, 211, 153, 0.2); color: #34d399; }
    </style>
</head>
<body>
    <div class="admin-container">
        <div class="header">
            <h2>پنل نظارت و مدیریت کل وظایف (Admin)</h2>
            <div>
                <span>خوش آمدید ادمین <strong><?= htmlspecialchars($_SESSION['username'] ?? 'مدیر') ?></strong></span> | 
                <a href="auth/logout.php" class="btn" style="background:#f87171;">خروج</a>
            </div>
        </div>

        <form method="GET" class="filter-bar">
            <input type="text" name="search" class="glass-input" placeholder="جستجوی عنوان وظیفه..." value="<?= htmlspecialchars($search) ?>">
            <select name="status" class="glass-input" style="background:#1e293b;">
                <option value="">همه وضعیت‌ها</option>
                <option value="in_progress" <?= $filter_status === 'in_progress' ? 'selected' : '' ?>>در حال انجام</option>
                <option value="completed" <?= $filter_status === 'completed' ? 'selected' : '' ?>>کامل شده</option>
            </select>
            <button type="submit" class="btn">اعمال فیلتر</button>
            <a href="index.php" class="btn" style="background: rgba(255,255,255,0.1); margin-right: 10px;">داشبورد اصلی</a>
        </form>

        <table>
            <thead>
                <tr>
                    <th>کاربر ایجادکننده</th>
                    <th>عنوان وظیفه</th>
                    <th>تاریخ ددلاین</th>
                    <th>وضعیت</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($tasks) > 0): foreach($tasks as $row): ?>
                <tr>
                    <td><strong><?= htmlspecialchars($row['username']) ?></strong></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['due_date']) ?></td>
                    <td>
                        <span class="status <?= $row['status'] === 'in_progress' ? 'status-in' : 'status-cp' ?>">
                            <?= $row['status'] === 'in_progress' ? 'در حال انجام' : 'کامل شده' ?>
                        </span>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="4" style="text-align:center; color:rgba(255,255,255,0.4);">هیچ وظیفه‌ای با این مشخصات یافت نشد.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>