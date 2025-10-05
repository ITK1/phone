<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$scoreId = intval($_GET['id'] ?? 0);
if (!$scoreId) {
    header('Location: scores.php');
    exit();
}

// Check if score exists
$score = fetchSingle("SELECT * FROM scores WHERE id = ?", [$scoreId]);
if (!$score) {
    $_SESSION['error_message'] = 'Không tìm thấy điểm số.';
    header('Location: scores.php');
    exit();
}

try {
    // Delete score
    executeQuery("DELETE FROM scores WHERE id = ?", [$scoreId]);
    $_SESSION['success_message'] = 'Xóa điểm số thành công!';
    
} catch (Exception $e) {
    $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa điểm số: ' . $e->getMessage();
}

header('Location: scores.php');
exit();
?>
