<?php
require_once '../core/connect.php';
$db = Database::getsql()->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $email = $_POST['email'] ?? '';
    $plan = $_POST['plan'] ?? '';

    if ($name && $email && $plan) {
        $stmt = $db->prepare("INSERT INTO memberships (name, email, plan, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->execute([$name, $email, $plan]);
        $success = "🎉 Đăng ký gói thành công!";
    } else {
        $error = "⚠️ Vui lòng điền đầy đủ thông tin!";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Đăng ký gói học viên</title>
    <link rel="stylesheet" href="../assets/style.css">
    <style>
        body { font-family: Arial; background: #f2f2f2; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 50px auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 2px 6px rgba(0,0,0,0.1); }
        h2 { text-align: center; }
        form { margin-top: 20px; }
        input, select { width: 100%; padding: 10px; margin-bottom: 15px; border-radius: 6px; border: 1px solid #ccc; }
        button { background: #007bff; color: white; border: none; padding: 10px 20px; border-radius: 8px; cursor: pointer; width: 100%; }
        button:hover { background: #0056b3; }
        .msg { text-align: center; font-weight: bold; }
    </style>
</head>
<body>
<div id="add-video">
    <div class="container-dk-tv">
        <div class="box">
            <div class="text-dk-tv"><h2>Đăng ký Gói Thành Viên</h2></div>
            <?php if (!empty($success)) echo "<div class='msg' style='color:green;'>$success</div>"; ?>
            <?php if (!empty($error)) echo "<div class='msg' style='color:red;'>$error</div>"; ?>
            <form method="POST">
                <div class="input-box">
                    <input type="text" name="name" placeholder="Họ và tên" required>
                </div>
                <div class="input-box">
                    <input type="email" name="email" placeholder="Email của bạn" required>
                </div>
                <div class="input-box">
                    <select name="plan" required>
                    <option value="">-- Chọn gói học --</option>
                    <option value="Tháng">Gói Tháng - 199.000đ</option>
                    <option value="6 Tháng">Gói 6 Tháng - 899.000đ</option>
                    <option value="Năm">Gói Năm - 1.499.000đ</option>
                </select>
                </div>
                <div class="btn"><button type="submit">Đăng ký gói</button></div>
            </form>
           <a href="index.php" style="display:block;text-align:center;margin-top:10px;">← Quay lại</a>
        </div>
    </div>
</div>
</body>
</html>
