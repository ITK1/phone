<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseId = intval($_POST['course_id'] ?? 0);
    $courseCode = trim($_POST['course_code'] ?? '');
    $courseName = trim($_POST['course_name'] ?? '');
    $credits = intval($_POST['credits'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    
    // Validation
    if (empty($courseCode)) {
        $errors[] = 'Mã khóa học không được để trống.';
    }
    
    if (empty($courseName)) {
        $errors[] = 'Tên khóa học không được để trống.';
    }
    
    if ($credits < 1 || $credits > 10) {
        $errors[] = 'Số tín chỉ phải từ 1 đến 10.';
    }
    
    // Check if course code exists (for new courses or when changing code)
    if ($courseId > 0) {
        // Editing existing course
        $existing = fetchSingle("SELECT id FROM courses WHERE course_code = ? AND id != ?", [$courseCode, $courseId]);
    } else {
        // Adding new course
        $existing = fetchSingle("SELECT id FROM courses WHERE course_code = ?", [$courseCode]);
    }
    
    if ($existing) {
        $errors[] = 'Mã khóa học đã tồn tại.';
    }
    
    if (empty($errors)) {
        try {
            if ($courseId > 0) {
                // Update existing course
                $sql = "UPDATE courses SET course_code = ?, course_name = ?, credits = ?, description = ? WHERE id = ?";
                executeQuery($sql, [$courseCode, $courseName, $credits, $description, $courseId]);
                $_SESSION['success_message'] = 'Cập nhật khóa học thành công!';
            } else {
                // Insert new course
                $sql = "INSERT INTO courses (course_code, course_name, credits, description) VALUES (?, ?, ?, ?)";
                executeQuery($sql, [$courseCode, $courseName, $credits, $description]);
                $_SESSION['success_message'] = 'Thêm khóa học thành công!';
            }
            
        } catch (Exception $e) {
            $_SESSION['error_message'] = 'Có lỗi xảy ra: ' . $e->getMessage();
        }
    } else {
        $_SESSION['error_message'] = implode('<br>', $errors);
    }
} else {
    $_SESSION['error_message'] = 'Yêu cầu không hợp lệ.';
}

header('Location: courses.php');
exit();
?>
