<?php
require_once '../models/Course.php';
$courseModel = new Course();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $courseModel->addCourses($_POST['name'], $_POST['teacher'],$_POST['time'], $_POST['description'], $_POST['price']);
    header("Location: courses.php");
    exit;
}

$courses = $courseModel->getAllCourses();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Khóa học</title>
    <link rel="stylesheet" href="../assets/style.css?v=1.0" />
</head>
<body>
<div id="course-adm">
    <div class="container">
        <div class="form-input">
            <div class="text">Bảng thêm khóa học</div>
            <form method="POST">
                <div class="input-group">  
                    <input type="text" name="name" placeholder="Tên khóa học" required>
                </div>
                <div class="input-group">
                    <input type="text" name="teacher" placeholder="Giáo viên">
                </div>
                    <div class="input-group">   
                   <input type="text" name="time"placeholder="thời gian">
                </div>
                   <div class="input-group">
                   <textarea name="description" placeholder="Mô tả"></textarea>
                </div>
                   <div class="input-group">
                   <input type="number" name="price" placeholder="Giá" step="0.01">
                </div class="btn-submit">
                   <button type="submit">Thêm khóa học</button>
                </div>
            </form>
        </div>
        <table border="1" cellpadding="5">
        <tr><th>ID</th><th>Tên</th><th>Thời Gian</th><th>Giáo viên</th><th>Giá</th><th>Xem</th></tr>
        <?php foreach ($courses as $c): ?>
        <tr>
            <td><?= $c['id'] ?></td>
            <td><?= $c['name'] ?></td>      
            <td><?=$c['time']?></td>
            <td><?= $c['teacher'] ?></td>
            <td><?= number_format($c['price'] ?? 0, 0) ?>đ</td>
            <td><a href="course_detail.php?id=<?= $c['id'] ?>">Chi tiết</a></td>
        </tr>
        <?php endforeach; ?>
        </table>
    </div>
</div>
</body>
</html>
