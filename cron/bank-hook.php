<?php
require_once '../config/config.php';
require_once '../src/Core/Database.php';
$db = Database::getsql()->getConnection();

// Token bảo mật (phải trùng với token bên webhook service)
$webhook_secret = "YOUR_WEBHOOK_TOKEN_HERE";

// Kiểm tra header
$headers = getallheaders();
if (empty($headers['X-Api-Key']) || $headers['X-Api-Key'] !== $webhook_secret) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

// Lấy dữ liệu JSON
$data = json_decode(file_get_contents("php://input"), true);

// Kiểm tra dữ liệu hợp lệ
if (!empty($data['description']) && !empty($data['amount'])) {
    $desc = trim($data['description']);
    $amount = (float)$data['amount'];
    $bank = $data['bank_name'] ?? 'Không rõ';
    $time = $data['time'] ?? date('Y-m-d H:i:s');

    // Nếu nội dung chứa mã nạp "NAP<user_id>"
    if (preg_match('/NAP(\d+)/', $desc, $match)) {
        $user_id = $match[1];

        // Cộng tiền vào tài khoản
        $stmt = $db->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);

        // Lưu vào bảng transactions
        $stmt = $db->prepare("INSERT INTO transactions (user_id, amount, description, bank_name, created_at) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $amount, $desc, $bank, $time]);

        echo json_encode(["status" => "success", "message" => "Giao dịch đã lưu"]);
    } else {
        echo json_encode(["status" => "ignored", "message" => "Không khớp nội dung NAP"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu dữ liệu"]);
}
