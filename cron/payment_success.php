<?php
require_once __DIR__ . '/../models/Payment.php';


    $payment = new Payment();

    $id = $_GET['$id'] ?? 0;
    $data = $payment->getTransaction($id);

    if(!$data){
        die("Không Tìm Thấy Giao Dịch");
    }

    $bank =   $data['$bank_name'];
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
</style>
</head>
<body>
<div class="box">
    <h2>✅ PAYMENT SUCCESSFUL</h2>
    <p><b>Server:</b> Student Management</p>
    <p><b>Transaction ID:</b> <?= $data['id'] ?></p>
    <p><b>Payment Method:</b> VietQR (<?= htmlspecialchars($bank) ?>)</p>
    <p><b>Time:</b> <?= $data['created_at'] ?></p>
    <p><b>Amount:</b> <?= number_format($data['amount']) ?> VND</p>
    <p><b>Status:</b> <span class="success"><?= strtoupper($data['status']) ?></span></p>

    <br>
    <a href="../public/index.php">⬅ Quay lại trang chính</a>
</div>
</body>
</html>