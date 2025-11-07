<?php
require_once '../models/Payment.php';

$payment = new Payment();

$webhook_secret = "YOUR_WEBHOOK_TOKEN_HERE";
$headers = getallheaders();
if(empty($headers['X-Api-Key']) || $headers['X-Api-Key'] !== $webhook_secret){
        http_response_code(403);
        echo json_encode(["error" => "Unauthorized"]);
        exit;
}

$data = json_decode(file_get_contents("php://input"),true);

if(!empty($data['description']) && !empty($data['amount'])){
    $desc =trim($data['description']);
    $amount = (float)$data['amount'];


    // nap tien 
    if(preg_match('/NAP(\d+)/i', $desc,$match)){
        $user_id = $match[1];
        $payment->addMoney($user_id, $amount);
        $payment->updateTransaction($data['transaction_id'] ?? 0 ,'success');
        echo json_encode(["status" =>"succes" , "type" => "recharge" ,"messager"]);
        exit;
    }


// mua goi: mua {user_id}_plan{plan_id}
if (preg_match('/MUA(\d+)_PLAN(\d+)/i', $desc, $match)) {
    $user_id = $match[1];
    $plan_id = $match[2];
    $plan = $payment->getPlan($plan_id);

    if ($plan && $amount >= $plan['price']) {
        $payment->createMembership($user_id, $plan_id);
        $payment->updateTransaction($data['transaction_id'] ?? 0, 'success');
        echo json_encode(["status" => "success", "type" => "membership", "message" => "Kích hoạt gói {$plan['plan_name']} cho user $user_id"]);
    } else {
        echo json_encode(["status" => "failed", "message" => "Số tiền không khớp hoặc gói không tồn tại"]);
    }
    exit;
}
echo json_encode(["status" => "ignored", "messager" => "Không Khớp Mẫu Nạp Tiền mua gói."]);
exit;

}else{
    http_response_code(400);
    echo json_encode(["error" => "Thiếu dữ liệu"]);

}
?>
