<?php
require_once __DIR__ . '/../controllers/Getpayment.php';

$controller = new GetpaymentController();
$user_id = 1; // giả sử user đăng nhập
$bank = "MB";
$account = "0779002304";
$account_name = "PHAN LE BA KHANG";
$desc = "NAP" . $user_id;

$amount = isset($_POST['amount']) ? (int)$_POST['amount'] : 0;
$transaction = null;

if ($amount > 0) {
    $transaction = $controller->create($user_id, $amount, $bank, $desc);
}
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
            text-align: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        input, button {
            padding: 10px;
            width: 100%;
            margin-top: 10px;
        }
        img {
            display: block;
            margin: 20px auto;
            border-radius: 8px;
        }
        .countdown {
            font-size: 20px;
            margin-top: 15px;
            font-weight: bold;
            color: #333;
        }
        .expired {
            color: red;
        }
        .notice {
            margin-top: 15px;
            font-size: 16px;
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

    <?php if ($transaction): 
        $expire_at = $transaction['expire_at'] ?? date('Y-m-d H:i:s', strtotime('+15 minutes'));
    ?>
        <h3>👉 Quét mã QR bên dưới để thanh toán</h3>
        <img src="https://img.vietqr.io/image/<?= $bank ?>-<?= $account ?>-compact2.jpg?accountName=<?= urlencode($account_name) ?>&amount=<?= $amount ?>&addInfo=<?= urlencode($desc) ?>" width="300">
        <p><b>Ngân hàng:</b> <?= $bank ?></p>
        <p><b>STK:</b> <?= $account ?></p>
        <p><b>Chủ TK:</b> <?= $account_name ?></p>
        <p><b>Nội dung:</b> <?= $desc ?></p>
        <p><b>Số tiền:</b> <?= number_format($amount) ?>đ</p>
        <hr>
        <h4>🕒 Thời gian giao dịch còn lại:</h4>
        <div id="countdown" class="countdown"></div>
        <div id="notice" class="notice"></div>

        <script>
            const expireTime = new Date("<?= $expire_at ?>").getTime();
            const countdownEl = document.getElementById("countdown");
            const noticeEl = document.getElementById("notice");

            const timer = setInterval(() => {
                const now = new Date().getTime();
                const distance = expireTime - now;

                if (distance <= 0) {
                    clearInterval(timer);
                    countdownEl.innerHTML = "⏰ Hết thời gian giao dịch!";
                    countdownEl.classList.add("expired");
                    noticeEl.innerHTML = "Vui lòng tạo lại mã QR để tiếp tục nạp tiền.";
                    
                    // Sau 5 giây quay lại trang index.php
                    setTimeout(() => {
                        window.location.href = "../index.php";
                    }, 5000);
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                countdownEl.innerHTML = `${minutes} phút ${seconds < 10 ? '0'+seconds : seconds} giây`;
            }, 1000);
        </script>
    <?php endif; ?>
</div>
</body>
</html>
