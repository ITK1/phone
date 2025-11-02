<?php
// Giả lập webhook test
$payload = [
    "description" => "NAP1",
    "amount" => 50000,
    "bank_name" => "MB Bank",
    "time" => date('Y-m-d H:i:s')
];

$ch = curl_init("http://localhost/QLSV/public/bank-hook.php");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/json",
    "X-Api-Key: YOUR_WEBHOOK_TOKEN_HERE"
]);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

echo "<pre>";
print_r($response);
echo "</pre>";
