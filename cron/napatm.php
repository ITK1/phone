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
    <style>
    body {
        font-family: Arial;
        background: #f4f4f4;
        padding: 30px;
    }

    .container {
        max-width: 500px;
        margin: auto;
        background: #fff;
        padding: 20px;
        border-radius: 10px;
    }

    input,
    button {
        padding: 10px;
        width: 100%;
        margin-top: 10px;
    }

    img {
        display: block;
        margin: 20px auto;
    }
    </style>
</head>

<body>
    <div class="container">
        <h2>💰 Nạp tiền tự động</h2>
        <form method="POST">
            <label>Nhập số tiền muốn nạp:</label>
            <input type="number" name="amount" placeholder="VD: 100000" required>
            <button type="submit">Tạo mã QR</button>
        </form>

        <?php if ($amount > 0): ?>
        <h3>👉 Quét mã QR bên dưới để thanh toán</h3>
        <img src="https://img.vietqr.io/image/<?= $bank ?>-<?= $account ?>-compact2.jpg?accountName=<?= urlencode($account_name) ?>&amount=<?= $amount ?>&addInfo=<?= urlencode($noidung) ?>"
            width="300">
        <p><b>Ngân hàng:</b> <?= $bank ?></p>
        <p><b>STK:</b> <?= $account ?></p>
        <p><b>Chủ TK:</b> <?= $account_name ?></p>
        <p><b>Nội dung chuyển khoản:</b> <?= $noidung ?></p>
        <p><b>Số tiền:</b> <?= number_format($amount) ?>đ</p>
        <?php endif; ?>
    </div>
</body>

</html>