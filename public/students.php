<?php
require_once __DIR__ . '/../controllers/StudentController.php';
    $controller = new StudentController();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $controller->store($_POST['name'], $_POST['email'], $_POST['dob']);
    }

    $student = $controller->index();
?>

<h2>Danh Sách Sinh Viên </h2>

<form method="POST">
    Tên: <input type="name" required>
    Email: <input type="email" required>
    Ngày Sinh : <input name="dob" type="date" required>
    <button>Thêm</button>
</form>

<table border="1" cellpadding="5">
<tr><th>ID</th><th>Tên</th><th>Email</th><th>Ngày sinh</th></tr>
<?php foreach ($student as $st): ?>
<tr>
    <td><?= $st['id'] ?></td>
    <td><?= $st['name'] ?></td>
    <td><?= $st['email'] ?></td>
    <td><?= $st['dob'] ?></td>
</tr>
<?php endforeach; ?>
</table>