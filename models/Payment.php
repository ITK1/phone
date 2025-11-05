<?php
require_once __DIR__ . '/../core/connect.php';

class Payment {
    private $conn;

    public function __construct() {
        $this->conn = Database::getsql()->getConnection();
    }

    // ✅ Tạo giao dịch mới
    public function createTransaction($user_id, $amount, $bank_code, $desc) {
        $data = $this->conn->prepare("
            INSERT INTO transactions (user_id, amount, bank_code, description, status, created_at)
            VALUES (?, ?, ?, ?, 'pending', NOW())
        ");
        $data->execute([$user_id, $amount, $bank_code, $desc]);
        return $this->conn->lastInsertId();
    }

    // ✅ Cập nhật trạng thái giao dịch
    public function updateTransaction($id, $status) {
        $stmt = $this->conn->prepare("UPDATE transactions SET status = ?, confirmed_at = NOW() WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    // ✅ Cộng tiền vào tài khoản người dùng
    public function addMoney($user_id, $amount) {
        $stmt = $this->conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
        $stmt->execute([$amount, $user_id]);
    }

    // ✅ Lấy thông tin 1 giao dịch
    public function getTransaction($id) {
        $stmt = $this->conn->prepare("SELECT * FROM transactions WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
?>
