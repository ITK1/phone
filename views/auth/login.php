<?php 
// Khởi tạo biến $message nếu nó chưa được set (để tránh lỗi)
$message = $message ?? '';
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Nhập Hệ Thống Quản Lý</title>
    <style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f4f4;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .login-container {
        background: #fff;
        padding: 20px 30px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        width: 300px;
    }

    h2 {
        text-align: center;
        color: #333;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
    }

    .form-group input {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .btn-login {
        width: 100%;
        padding: 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
    }

    .btn-login:hover {
        background-color: #0056b3;
    }

    .message {
        color: red;
        text-align: center;
        margin-bottom: 10px;
        font-weight: bold;
    }

    .register-link {
        text-align: center;
        margin-top: 15px;
        font-size: 14px;
    }
    </style>
</head>

<body>
    <div class="login-container">
        <h2>Đăng Nhập</h2>

        <?php 
        // Hiển thị thông báo lỗi hoặc trạng thái (Ví dụ: "Sai mật khẩu", "Chờ Admin duyệt")
        if (!empty($message)): 
        ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>

        <form action="index.php?controller=auth&action=processLogin" method="POST">
            <div class="form-group">
                <label for="username">Tên Đăng Nhập:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật Khẩu:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn-login">Đăng Nhập</button>
        </form>

        <div class="register-link">
            <p>Chưa có tài khoản? <a href="index.php?controller=auth&action=register">Đăng ký tại đây</a>.</p>
        </div>
    </div>
</body>

</html>