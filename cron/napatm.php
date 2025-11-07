<?php
session_start();
require_once __DIR__ . '/../models/Payment.php';

$payment = new Payment();

// ✅ Kiểm tra người dùng đã đăng nhập hay chưa
if (empty($_SESSION['user_id'])) {
    die("❌ Bạn cần đăng nhập để mua gói membership!");
}

$user_id = $_SESSION['user_id']; // Lấy ID user thật
$bank = "MB";
$account = "0779002304";
$account_name = "PHAN LE BA KHANG";

$plan_id = $_GET['plan_id'] ?? 0;
$plan = $payment->getPlan($plan_id);

if (!$plan) {
    die("❌ Gói membership không tồn tại!");
}

$amount = $plan['price'];
$desc = "MUA{$user_id}_PLAN{$plan_id}";
$transaction_id = $payment->createTransaction($user_id, $amount, $desc, $bank);
$expire_at = date('Y-m-d H:i:s', strtotime('+15 minutes'));
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán Membership</title>
<style>
body {font-family: Arial; background: #f4f4f4; padding: 30px;}
.container {max-width: 500px; margin:auto; background:#fff; padding:20px; border-radius:10px; text-align:center; box-shadow:0 2px 10px rgba(0,0,0,0.1);}
img {display:block; margin:20px auto; border-radius:8px;}
.countdown {font-size:20px; margin-top:15px; font-weight:bold;}
.expired {color:red;}
</style>
</head>
<body>
<div class="container">
    <h2>💳 Thanh toán gói <?= htmlspecialchars($plan['plan_name']) ?></h2>

    <img src="https://img.vietqr.io/image/<?= $bank ?>-<?= $account ?>-compact2.jpg?accountName=<?= urlencode($account_name) ?>&amount=<?= $amount ?>&addInfo=<?= urlencode($desc) ?>" width="300">

    <p><b>Ngân hàng:</b> <?= $bank ?></p>
    <p><b>STK:</b> <?= $account ?></p>
    <p><b>Chủ TK:</b> <?= $account_name ?></p>
    <p><b>Nội dung chuyển khoản:</b> <span style="color:blue"><?= $desc ?></span></p>
    <p><b>Số tiền:</b> <span style="color:green"><?= number_format($amount) ?>đ</span></p>

    <h4>🕒 Thời gian còn lại:</h4>
    <div id="countdown" class="countdown"></div>

    <script>
    const expireTime = new Date("<?= $expire_at ?>").getTime();
    const countdownEl = document.getElementById("countdown");
    const timer = setInterval(() => {
        const now = new Date().getTime();
        const distance = expireTime - now;
        if (distance <= 0) {
            clearInterval(timer);
            countdownEl.innerHTML = "⏰ Hết thời gian giao dịch!";
            countdownEl.classList.add("expired");
            setTimeout(() => window.location.href = "../index.php", 5000);
            return;
        }
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);
        countdownEl.innerHTML = `${minutes} phút ${seconds < 10 ? '0'+seconds : seconds} giây`;
    }, 1000);
    </script>
</div>
</body>
</html>
