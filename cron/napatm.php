<?php
require_once __DIR__ . '/../Core/connect.php';

$db = (new Database())->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = 1; // giả lập user (sau này bạn lấy từ $_SESSION)
    $bankAccount = $_POST['bank_account'];
    $bankName = $_POST['bank_name'];
    $accountHolder = $_POST['account_holder'];
    $content = $_POST['content'];
    $amount = $_POST['amount'];

    $stmt = $db->prepare("INSERT INTO transactions (user_id, bank_account, bank_name, account_holder, content, amount) 
                          VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$userId, $bankAccount, $bankName, $accountHolder, $content, $amount]);

    // Tạo QR động theo định dạng VietQR
    $qrData = "https://img.vietqr.io/image/{$bankName}-{$bankAccount}-qr_only.png?amount={$amount}&addInfo=" . urlencode($content) . "&accountName=" . urlencode($accountHolder);
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Nạp ATM tự động</title>
    <style>
    form {
        max-width: 400px;
        margin: 40px auto;
        font-family: Arial;
    }

    input,
    button {
        width: 100%;
        padding: 10px;
        margin: 5px 0;
    }

    img {
        width: 100%;
        margin-top: 20px;
    }
    </style>
</head>

<body>
    <h2 align="center">Nạp tiền tự động qua ngân hàng</h2>

    <form method="POST">
        <input type="text" name="bank_account" placeholder="Số tài khoản" required>
        <input type="text" name="bank_name" placeholder="Ngân hàng (ví dụ: vietcombank)" required>
        <input type="text" name="account_holder" placeholder="Tên chủ tài khoản" required>
        <input type="text" name="content" placeholder="Nội dung chuyển khoản (VD: NAP123)" required>
        <input type="number" name="amount" placeholder="Số tiền (VD: 10000)" required>
        <button type="submit">Tạo mã QR</button>
    </form>

    <?php if (isset($qrData)): ?>
    <h3 align="center">Quét mã để chuyển khoản</h3>
    <img src="<?= $qrData ?>" alt="QR chuyển khoản">
    <?php endif; ?>
</body>

</html>