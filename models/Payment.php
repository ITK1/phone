<?php
require_once __DIR__ . '/../core/connect.php';

class Payment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getsql()->getConnection();
    }

    /** ✅ Cộng tiền vào tài khoản user */
    public function addMoney($user_id, $amount) {
        $stmt = $this->conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        return $stmt->execute([$amount, $user_id]);
    }

    /** ✅ Trừ tiền (nếu đủ số dư) */
    public function deductMoney($user_id, $amount) {
        $stmt = $this->conn->prepare("UPDATE users SET balance = balance - ? WHERE id = ? AND balance >= ?");
        return $stmt->execute([$amount, $user_id, $amount]);
    }

    /** ✅ Cập nhật trạng thái giao dịch */
    public function updateTransaction($transaction_id, $status) {
        $stmt = $this->conn->prepare("UPDATE transactions SET status = ?, confirmed_at = NOW() WHERE id = ?");
        return $stmt->execute([$status, $transaction_id]);
    }

    /** ✅ Tạo giao dịch mới */
    public function createTransaction($user_id, $amount, $description, $bank_code = 'MB') {
        $stmt = $this->conn->prepare("
            INSERT INTO transactions (user_id, amount, bank_code, description, status)
            VALUES (?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$user_id, $amount, $bank_code, $description]);
        return $this->conn->lastInsertId();
    }

    /** ✅ Kích hoạt gói membership */
    public function createMembership($user_id, $plan_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO memberships (user_id, plan_id, start_date, status)
            VALUES (?, ?, NOW(), 'active')
        ");
        return $stmt->execute([$user_id, $plan_id]);
    }

    /** ✅ Lấy thông tin gói membership */
    public function getPlan($plan_id) {
        $stmt = $this->conn->prepare("SELECT * FROM membership_plans WHERE id = ?");
        $stmt->execute([$plan_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /** ✅ Lấy thông tin giao dịch theo ID */
    public function getTransaction($id) {
        try {
            $stmt = $this->conn->prepare("SELECT * FROM transactions WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Lỗi getTransaction: " . $e->getMessage());
            return null;
        }
    }
}
?>
