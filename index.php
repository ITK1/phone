<?php
// index.php - Trang chính của hệ thống quản lý sinh viên và khóa học
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/student.php';
require_once __DIR__ .'/models/course.php';
require_once __DIR__. './include/header.php';


$studentModel = new student();
$courseModel = new Course();

$totalStudents = $studentModel->countStudents();
$totalCourses = $courseModel->countCourses();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>🏫 Hệ thống quản lý sinh viên</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <style>
        body {
            background: #f6f8fa;
        }
        .card {
            border-radius: 1rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        nav {
            background: #007bff;
        }
        nav a {
            color: #fff !important;
            font-weight: 500;
        }
        .dashboard {
            margin-top: 40px;
        }
    </style>
</head>
<body>



<div class="container dashboard text-center">
    <h2 class="mb-4">Trang quản trị hệ thống</h2>

    <div class="row justify-content-center">
        <div class="col-md-4 mb-3">
            <div class="card p-4">
                <h4>👨‍🎓 Tổng số sinh viên</h4>
                <h2 class="text-primary"><?php echo $totalStudents; ?></h2>
                <a href="students.php" class="btn btn-outline-primary mt-2">Xem chi tiết</a>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card p-4">
                <h4>📘 Tổng số khóa học</h4>
                <h2 class="text-success"><?php echo $totalCourses; ?></h2>
                <a href="courses.php" class="btn btn-outline-success mt-2">Xem chi tiết</a>
            </div>
        </div>
    </div>
</div>

</body>
</html>
