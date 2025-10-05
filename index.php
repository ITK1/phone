<?php
// index.php - Trang chính của hệ thống quản lý sinh viên và khóa học
require_once __DIR__ . '/config/db.php';
require_once __DIR__ . '/models/student.php';
require_once __DIR__ .'/models/course.php';

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
    <link rel="stylesheet" href="./assets/style.css">
    
</head>
<body>

<div id="main">
      <div id="header">
        <div class="nav">
          <ul>
            <li><a href="index.php"></a>Trang chủ</li>
            <li class="shownav">
              <a href="courses.php">Khóa học</a>
              <ul class="navcon">
                <li><a href="students.php">Sinh Viên</a></li>
                <li><a href="">Ai eo</a></li>
                <li><a href=""></a>Anh Van</li>
              </ul>
            </li>
            <li><a href=""></a>Thời khóa biểu</li>
            <li><a href=""></a>Lịch dạy</li>
            <li><a href=""></a>Thông tin</li>
            <li><a href="./student-managementt/index.php">web</a></li>
          </ul>
        </div>
      </div>



<div id="container">
        <h1>Quản lý Sinh Viên</h1>

        <div class="stat-box student">
          <div class="stat-title">Tổng số sinh viên</div>
          <h2 class="stat-number text-primary">
            <?php echo $totalStudents; ?>
          </h2>
        </div>

        <div class="stat-box course">
          <div class="stat-title">Tổng số khóa học</div>
          <h2 class="stat-number text-success">
            <?php echo $totalCourses; ?>
          </h2>
        </div>

</body>
</html>
