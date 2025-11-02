<?php
require_once '../config/config.php';
require_once '../Core/connect.php';
$db = Database::getsql()->getConnection();

$data = $db->query("SELECT t.*, u.name AS username FROM transactions t 
JOIN users u ON u.id = t.user_id 
ORDER BY t.id DESC")->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Lịch sử giao dịch</title>
    <style>
    body {
        font-family: Arial;
        padding: 30px;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    th,
    td {
        border: 1px solid #ccc;
        padding: 8px;
        text-align: center;
    }

    th {
        background: #007bff;
        color: white;
    }
    </style>
</head>

<body>
    <h2>📜 Lịch sử nạp tiền</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Tên người dùng</th>
            <th>Số tiền</th>
            <th>Nội dung</th>
            <th>Ngân hàng</th>
            <th>Thời gian</th>
        </tr>
        <?php foreach($data as $t): ?>
        <tr>
            <td><?= $t['id'] ?></td>
            <td><?= htmlspecialchars($t['username']) ?></td>
            <td><?= number_format($t['amount'], 0, ',', '.') ?>đ</td>
            <td><?= htmlspecialchars($t['description']) ?></td>
            <td><?= htmlspecialchars($t['bank_name']) ?></td>
            <td><?= $t['created_at'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>

</html>