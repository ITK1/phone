<?php
require_once '../config/config.php';
require_once '../Core/connect.php';
$db = Database::getsql()->getConnection();

// Giả sử user_id = 1 (bạn có thể lấy từ session khi đăng nhập)
$user_id = 1;
$bank = "MB";           // Mã ngân hàng (VD: MB, VCB, TCB)
$account = "0779002304"; // STK ngân hàng
$account_name = "PHAN LE BA KHANG";

// Sinh nội dung chuyển khoản (NAP + user_id)
$noidung = "napthe" . $user_id;

// Nếu nhập số tiền
$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Nạp tiền tự động</title>
  <link rel="stylesheet" href="../assets/style.css"/>
</head>
<body>
  <div id="qr">
    <div class="container">
        <h2 class="text">Nạp tiền tự động</h2>
      <form method="POST">
        <div class="input-box">
          <label>Nhập số tiền muốn nạp:</label><br>
          <input type="number" name="amount" placeholder="VD: 100000" required>
          
        </div>
      <button type="submit">Tạo mã QR</button>
      </form>

      <?php if ($amount > 0): ?>
      <div class="box-qr">
      <h3>👉 Quét mã QR bên dưới để thanh toán</h3>
      <div class="img-qr">
        <img src="https://img.vietqr.io/image/<?= $bank ?>-<?= $account ?>-compact2.jpg?accountName=<?= urlencode($account_name) ?>&amount=<?= $amount ?>&addInfo=<?= urlencode($noidung) ?>" width="300">
      </div>
      <div class="box-tt">
        <div class="box">
          <div>
            <div>Ngân hàng:</div>
          </div>
          <div class="input-nhap"> <?= $bank ?></div>
        </div>
        <div class="box">
          <div>
            <div>STK:</div>
          </div>
          <div class="input-nhap"> <?= $account ?></div>
        </div>
        <div class="box">
          <div>
            <div>Chủ TK:</div>
          </div>
          <div class="input-nhap"> <?= $account_name ?></div>
        </div> 
         <div class="box"> 
          <div>
            <div for="">Nội dung chuyển khoản:</div>
          </div>
          <div class="input-nhap"> <?= $noidung ?></div>
         </div> 
        <div class="box"> 
          <div>
              <div>Số tiền:</div>
          </div>
          <div class="input-nhap"> <?= number_format($amount) ?>đ</div>
        </div>  
          <?php endif; ?>
      </div>
    </div>
    </div>i
  </div>  
</body>
</html>
