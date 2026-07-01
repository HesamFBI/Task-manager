<?php
// process.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// اتصال به دیتابیس از پوشه config مطابق تصویر image_82ceda.png
require_once __DIR__ . '/config/db.php';

// اطمینان از لاگین بودن کاربر
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_REQUEST['action'] ?? '';

header('Content-Type: application/json; charset=utf-8');

switch ($action) {
    
    // 1. READ (دریافت لیست تسک‌ها)
    case 'fetch_tasks':
        try {
            $stmt = $pdo->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY id DESC");
            $stmt->execute([$user_id]);
            $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($tasks);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 2. CREATE (ایجاد تسک جدید)
    case 'create_task':
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $category = $_POST['category'] ?? 'کاری';
        $due_date = $_POST['due_date'] ?? date('Y-m-d');

        if (empty($title)) {
            echo json_encode(['status' => 'error', 'message' => 'عنوان نمی‌تواند خالی باشد']);
            exit;
        }

        try {
            $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, category, due_date, status) VALUES (?, ?, ?, ?, ?, 'in_progress')");
            $stmt->execute([$user_id, $title, $description, $category, $due_date]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 3. UPDATE (ویرایش کامل عنوان، توضیحات و دسته‌بندی تسک)
    case 'update_task':
        $id = $_POST['id'] ?? 0;
        $title = $_POST['title'] ?? '';
        $description = $_POST['description'] ?? '';
        $category = $_POST['category'] ?? 'کاری';

        try {
            $stmt = $pdo->prepare("UPDATE tasks SET title = ?, description = ?, category = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$title, $description, $category, $id, $user_id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 4. UPDATE STATUS (تغییر وضعیت به تیک خورده یا ضربدر خورده)
    case 'update_status':
        $id = $_POST['id'] ?? 0;
        $status = $_POST['status'] ?? 'in_progress'; // values: in_progress, completed, failed

        try {
            $stmt = $pdo->prepare("UPDATE tasks SET status = ? WHERE id = ? AND user_id = ?");
            $stmt->execute([$status, $id, $user_id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    // 5. DELETE (حذف تسک)
    case 'delete_task':
        $id = $_POST['id'] ?? 0;

        try {
            $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->execute([$id, $user_id]);
            echo json_encode(['status' => 'success']);
        } catch (PDOException $e) {
            echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
        }
        break;

    default:
        echo json_encode(['status' => 'error', 'message' => 'عملیات نامعتبر است']);
        break;
}