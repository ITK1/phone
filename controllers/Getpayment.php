<?php
require_once __DIR__ . '/../models/Payment.php';

class GetpaymentController {
    private $payment;

    public function __construct() {
        $this->payment = new Payment();
    }

    public function create($user_id, $amount, $bank, $desc) {
        // Tạo giao dịch
        $transactionId = $this->payment->createTransaction($user_id, $amount, $bank, $desc);
        return $this->payment->getTransaction($transactionId);
    }
}
?>
