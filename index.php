<?php
require_once __DIR__ . '/models/Student.php';
require_once __DIR__ . '/models/Course.php';

$studentModel = new Student();
$courseModel = new Course();

$totalStudents = $studentModel->countcStudents();
$totalCourses = $courseModel->countcourses();

include __DIR__ . '/views/header.php';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>🏫 Hệ thống quản lý sinh viên</title>
    <link rel="stylesheet" href="./assets/style.css">

</head>

<body>

    



    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand text-white" href="index.php">📚 Quản lý sinh viên</a>
            <div>
                <a href="students.php" class="nav-link d-inline text-white">👨‍🎓 Sinh viên</a>
                <a href="courses.php" class="nav-link d-inline text-white">📘 Khóa học</a>
            </div>
        </div>
    </nav>

    <div id="container-adm">
        <h1>Quản lý Sinh Viên</h1>
        <div class="box-tong">
            <div class="box-show">
                <div class="title-text">TỔNG SỐ SINH VIÊN</div>
                <h2 class="text-number">
                    <?php echo $totalStudents; ?>
                    200
                </h2>
            </div>

            <div class="box-show">
                <div class="title-text">TỔNG SỐ KHÁO HỌC</div>
                <h2 class="text-number">
                    <?php echo $totalCourses; ?>
                    100
                </h2>
            </div>
        </div>
    </div>

    <?php include __DIR__ . '/views/footer.php'; ?>

    