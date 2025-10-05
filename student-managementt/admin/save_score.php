<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $scoreId = intval($_POST['score_id'] ?? 0);
    $studentId = intval($_POST['student_id'] ?? 0);
    $courseId = intval($_POST['course_id'] ?? 0);
    $midtermScore = $_POST['midterm_score'] ? floatval($_POST['midterm_score']) : null;
    $finalScore = $_POST['final_score'] ? floatval($_POST['final_score']) : null;
    $assignmentScore = $_POST['assignment_score'] ? floatval($_POST['assignment_score']) : null;
    $totalScore = $_POST['total_score'] ? floatval($_POST['total_score']) : null;
    $grade = trim($_POST['grade'] ?? '');
    $semester = trim($_POST['semester'] ?? '');
    $academicYear = trim($_POST['academic_year'] ?? '');
    
    // Validation
    if (!$studentId) {
        $errors[] = 'Vui lòng chọn sinh viên.';
    }
    
    if (!$courseId) {
        $errors[] = 'Vui lòng chọn khóa học.';
    }
    
    if (empty($semester)) {
        $errors[] = 'Học kỳ không được để trống.';
    }
    
    if (empty($academicYear)) {
        $errors[] = 'Năm học không được để trống.';
    }
    
    // Check if student is enrolled in the course
    $enrollment = fetchSingle("SELECT id FROM student_courses WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
    if (!$enrollment) {
        $errors[] = 'Sinh viên chưa đăng ký khóa học này.';
    }
    
    if (empty($errors)) {
        try {
            if ($scoreId > 0) {
                // Update existing score
                $sql = "UPDATE scores SET student_id = ?, course_id = ?, midterm_score = ?, final_score = ?, 
                        assignment_score = ?, total_score = ?, grade = ?, semester = ?, academic_year = ? 
                        WHERE id = ?";
                executeQuery($sql, [$studentId, $courseId, $midtermScore, $finalScore, $assignmentScore, 
                                   $totalScore, $grade, $semester, $academicYear, $scoreId]);
                $_SESSION['success_message'] = 'Cập nhật điểm số thành công!';
            } else {
                // Check if score already exists for this student, course, semester and academic year
                $existing = fetchSingle("SELECT id FROM scores WHERE student_id = ? AND course_id = ? AND semester = ? AND academic_year = ?", 
                                       [$studentId, $courseId, $semester, $academicYear]);
                if ($existing) {
                    $errors[] = 'Điểm số cho sinh viên này đã tồn tại trong học kỳ và năm học này.';
                } else {
                    // Insert new score
                    $sql = "INSERT INTO scores (student_id, course_id, midterm_score, final_score, 
                            assignment_score, total_score, grade, semester, academic_year) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    executeQuery($sql, [$studentId, $courseId, $midtermScore, $finalScore, $assignmentScore, 
                                       $totalScore, $grade, $semester, $academicYear]);
                    $_SESSION['success_message'] = 'Thêm điểm số thành công!';
                }
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

header('Location: scores.php');
exit();
?>
