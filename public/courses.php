<?php
require_once '../models/Course.php';

$courseModel = new Course();
$courses = $courseModel->countCourses();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Danh sách khóa học</title>
</head>
<body>
<h2>📘 Danh sách Khóa học</h2>
<table border="1" cellpadding="5">
    <tr>
        <th>ID</th>
        <th>Tên khóa học</th>
        <th>Giáo viên</th>
    </tr>
    <?php foreach ($courses as $course): ?>
        <tr>
            <td><?= $course['id'] ?></td>
            <td><?= $course['name'] ?></td>
            <td><?= $course['teacher'] ?></td>
        </tr>
    <?php endforeach; ?>
</table>
</body>
</html>
