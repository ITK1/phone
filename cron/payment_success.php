<?php
require_once __DIR__ . '/../models/Payment.php';

$payment = new Payment();

// ✅ Lấy ID giao dịch thật (qua GET, ví dụ: payment_success.php?id=5)
$id = $_GET['id'] ?? 0;

// ✅ Lấy dữ liệu giao dịch từ database
$data = $payment->getTransaction($id);

if (!$data) {
    die("❌ Không tìm thấy giao dịch!");
}

// ✅ Thông tin cố định (có thể lấy từ DB nếu muốn)
$bank = $data['bank_name'] ?? "MB";
$account = "0779002304";
$account_name = "PHAN LE BA KHANG";
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Thanh toán thành công</title>
<style>
body { font-family: Arial; background: #f9f9f9; text-align: center; padding: 50px; }
.box { background: #fff; border-radius: 10px; padding: 30px; max-width: 500px; margin: auto; box-shadow: 0 2px 8px rgba(0,0,0,0.1); }
.success { color: green; font-weight: bold; font-size: 20px; }
.fail { color: red; font-weight: bold; font-size: 20px; }
</style>
</head>
<body>
<div class="box">
    <h2>✅ PAYMENT SUCCESSFUL</h2>
    <p><b>Server:</b> Student Management</p>
    <p><b>Transaction ID:</b> <?= htmlspecialchars($data['id']) ?></p>
    <p><b>Payment Method:</b> VietQR (<?= htmlspecialchars($bank) ?>)</p>
    <p><b>Account:</b> <?= htmlspecialchars($account_name) ?> - <?= htmlspecialchars($account) ?></p>
    <p><b>Time:</b> <?= htmlspecialchars($data['created_at']) ?></p>
    <p><b>Amount:</b> <?= number_format($data['amount']) ?> VND</p>
    <p><b>Status:</b>
        <?php if (strtolower($data['status']) === 'success'): ?>
            <span class="success"><?= strtoupper($data['status']) ?></span>
        <?php else: ?>
            <span class="fail"><?= strtoupper($data['status']) ?></span>
        <?php endif; ?>
    </p>

    <br>
    <a href="../public/index.php">⬅ Quay lại trang chính</a>
</div>
</body>
</html>
