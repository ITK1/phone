<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$studentId = intval($_GET['id'] ?? 0);
if (!$studentId) {
    header('Location: students.php');
    exit();
}

// Get student info
$student = fetchSingle("SELECT * FROM students WHERE id = ?", [$studentId]);
if (!$student) {
    $_SESSION['error_message'] = 'Không tìm thấy sinh viên.';
    header('Location: students.php');
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();
    
    // Delete user account first (due to foreign key constraint)
    executeQuery("DELETE FROM users WHERE student_id = ?", [$studentId]);
    
    // Delete student (this will cascade delete related records)
    executeQuery("DELETE FROM students WHERE id = ?", [$studentId]);
    
    $pdo->commit();
    
    $_SESSION['success_message'] = 'Xóa sinh viên thành công!';
    
} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa sinh viên: ' . $e->getMessage();
}

header('Location: students.php');
exit();
?>
