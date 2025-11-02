<?php
require_once '../models/Course.php';
require_once '../Core/connect.php';

$courseModel = new Course();

if (!isset($_GET['id'])) {
    die("❌ Không tìm thấy khóa học");
}

$id = intval($_GET['id']);
$course = $courseModel->getCourseById($id);

if (!$course) {
    die("❌ Khóa học không tồn tại");
}

// Xử lý đăng ký khóa học
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['student_name'] ?? '';
    $email = $_POST['student_email'] ?? '';

    if ($name && $email) {
        $db = Database::getsql()->getConnection();
        $stmt = $db->prepare("INSERT INTO enrollments (course_id, student_name, student_email, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$id, $name, $email]);
        $success = "🎉 Đăng ký khóa học thành công!";
    } else {
        $error = "⚠️ Vui lòng nhập đầy đủ thông tin.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chi tiết khóa học</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: Arial, sans-serif; background: #f8f8f8; margin: 0; padding: 0; }
        .container { max-width: 900px; margin: 40px auto; background: #fff; border-radius: 10px; padding: 30px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        .title { font-size: 28px; font-weight: bold; margin-bottom: 15px; }
        .teacher { color: #333; margin-bottom: 10px; }
        .desc { color: #555; margin-bottom: 20px; line-height: 1.6; }
        form { background: #f2f2f2; padding: 20px; border-radius: 8px; }
        input { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; }
        button { background: #ff6600; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; }
        button:hover { background: #e65c00; }
        .msg { text-align: center; margin: 10px 0; font-weight: bold; }
        a.back { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title"><?= htmlspecialchars($course['name']) ?></div>
        <div class="teacher"><b>Giảng viên:</b> <?= htmlspecialchars($course['teacher']) ?></div>
        <div class="desc"><?= nl2br(htmlspecialchars($course['description'])) ?></div>
        <div><b>Giá khóa học:</b> <?= number_format($course['price'], 0, ',', '.') ?>đ</div>

        <h3>Đăng ký học ngay</h3>
        <?php if (!empty($success)) echo "<div class='msg' style='color:green;'>$success</div>"; ?>
        <?php if (!empty($error)) echo "<div class='msg' style='color:red;'>$error</div>"; ?>

        <form method="POST">
            <input type="text" name="student_name" placeholder="Họ và tên của bạn" required>
            <input type="email" name="student_email" placeholder="Email liên hệ" required>
            <button type="submit">Đăng ký ngay</button>
        </form>

        <a href="index.php" class="back">← Quay lại danh sách khóa học</a>
    </div>
</body>
</html>
