<?php
require_once '../models/Payment.php';
$payment = new Payment();
$webhook_secret = "YOUR_WEBHOOK_TOKEN_HERE";

$headers = getallheaders();
if (empty($headers['X-Api-Key']) || $headers['X-Api-Key'] !== $webhook_secret) {
    http_response_code(403);
    echo json_encode(["error" => "Unauthorized"]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!empty($data['description']) && !empty($data['amount'])) {
    $desc = trim($data['description']);
    $amount = (float)$data['amount'];

    if (preg_match('/NAP(\d+)/i', $desc, $match)) {
        $user_id = $match[1];
        $payment->addMoney($user_id, $amount);

        // Cập nhật trạng thái giao dịch thành công
        $payment->updateTransaction($data['transaction_id'] ?? 0, 'success');

        echo json_encode(["status" => "success", "message" => "Giao dịch đã cập nhật"]);
    } else {
        echo json_encode(["status" => "ignored", "message" => "Không khớp nội dung NAP"]);
    }
} else {
    http_response_code(400);
    echo json_encode(["error" => "Thiếu dữ liệu"]);
}
?>
