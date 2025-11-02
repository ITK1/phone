<?php
require_once __DIR__ . '/../controllers/StudentController.php';
    $controller = new StudentController();

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $controller->store($_POST['name'], $_POST['email'], $_POST['phone']);
    }

    $student = $controller->index();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    
</body>
</html>
<div id="course-adm">
    <div class="container">
        <div class="form-input">
        <div class="text">Danh Sách Sinh Viên </div>

        <form method="POST">
             <div class="input-group">
                <input type="name" required placeholder="Tên học sinh">
             </div>
             <div class="input-group">
                <input type="email" required placeholder="Địa chỉ email">
             </div>
             <div class="input-group">
                
                <input name="phone" type="date" required>
             </div>
             <div class="btn-submit">
                <button type="submit">Thêm</button>
                </div>
        </form>
        </div>
    </div>
<div class="danhsach">
     <div class="table-main">
        <div class="header">
        <div>ID</div>
        <div>Tên</div>
        <div>Email</div>
        <div>Ngày sinh</div>
    </div>
    <?php foreach ($student as $st): ?>
    <div class="content"><tr>
        <div><?= $st['id'] ?></div>
        <div><?= $st['name'] ?></div>
        <div><?= $st['email'] ?></div>
        <div><?= $st['phone'] ?></div>
     </div>
    <?php endforeach; ?>
</div>

</div>