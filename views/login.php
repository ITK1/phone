<?php 
// Khởi tạo biến $message nếu nó chưa được set (để tránh lỗi)
$message = $message ?? '';
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
    <div id="login">
        <div class="login-main">
            <div class="login-container">
                <div class="header-text">
                    <div class="h1">Hello, Welcom!</div>
                    <div class="no-tk">
                        <div ><a href="index.php?controller=auth&action=register">Chưa có tài khoản</a></div>
                    </div>
                        <button class="btn-register">register</button>
            </div>

        <div class="login-login">
            <div class="login-header">Login</div>
        <?php 
        // Hiển thị thông báo lỗi hoặc trạng thái (Ví dụ: "Sai mật khẩu", "Chờ Admin duyệt")
        if (!empty($message)): 
        ?>
        <p class="message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
            
        <form
          action="index.php?controller=auth&action=processLogin"
          method="POST"
        > 
            <div class="form-group">
              <input type="text" id="username" name="username" required placeholder="Username"/>
            </div>
            <div class="form-group">
              <input type="password" id="password" name="password" required placeholder="PassWord"/>
            </div>
            
          <div class="login-group">
            <div class="forgot-pass">
              <a href="">Quên mật khẩu</a>
            </div>
            <div class="btn-login"><button type="submit" >Login</button></div>
          </div>
          </div>
          
          
        </form>

        
            </div>
        </div>
    </div>
</body>

</html>

