<?php
require_once __DIR__ . '/models/Student.php';
require_once __DIR__ . '/models/Course.php';

$studentModel = new Student();
$courseModel = new Course();

$totalStudents = $studentModel->countStudents();
$totalCourses = $courseModel->countCourses();

include __DIR__ . '/views/header.php';
?>
<link rel="stylesheet" href="assets/css/style.css">
<h2 class="text-center mb-4">📊 Bảng điều khiển hệ thống</h2>
<div class="row text-center">
    <div class="col-md-6">
        <div class="card p-4 mb-3 shadow-sm">
            <h4>👨‍🎓 Sinh viên</h4>
            <h2 class="text-primary"><?= $totalStudents ?></h2>
            <a href="../views/students/list.php" class="btn btn-outline-primary">Quản lý sinh viên</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card p-4 mb-3 shadow-sm">
            <h4>📘 Khóa học</h4>
            <h2 class="text-success"><?= $totalCourses ?></h2>
            <a href="../views/courses/list.php" class="btn btn-outline-success">Quản lý khóa học</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/views/footer.php'; ?>
