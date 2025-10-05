<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$courseId = intval($_GET['id'] ?? 0);
if (!$courseId) {
    header('Location: courses.php');
    exit();
}

// Check if course exists
$course = fetchSingle("SELECT * FROM courses WHERE id = ?", [$courseId]);
if (!$course) {
    $_SESSION['error_message'] = 'Không tìm thấy khóa học.';
    header('Location: courses.php');
    exit();
}

// Check if course has enrolled students
$enrollments = fetchSingle("SELECT COUNT(*) as count FROM student_courses WHERE course_id = ?", [$courseId]);
if ($enrollments['count'] > 0) {
    $_SESSION['error_message'] = 'Không thể xóa khóa học này vì đã có sinh viên đăng ký.';
    header('Location: courses.php');
    exit();
}

try {
    // Delete course
    executeQuery("DELETE FROM courses WHERE id = ?", [$courseId]);
    $_SESSION['success_message'] = 'Xóa khóa học thành công!';
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa khóa học: ' . $e->getMessage();
}

header('Location: courses.php');
exit();
?>
