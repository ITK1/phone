<?php
require_once __DIR__ . '/../config/db.php';

class User {
    private $conn;
    private $table_name = "user";

    // Thuộc tính: id, username, password, role, is_approved

    public function __construct() {
        $database = new Data();
        $this->conn = $database->getConnection();
    }

    // --- 1. Đăng ký tài khoản (Tạo User) ---
    public function register() {
        // Mặc định role là 'teacher' và is_approved là 0 (Chờ duyệt)
        $query = "INSERT INTO " . $this->table_name . " (username, password, role, is_approved) VALUES (:username, :password, 'teacher', 0)";
        
        $stmt = $this->conn->prepare($query);

        // Bảo mật: Mã hóa mật khẩu (HASHING)
        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);

        // Gán tham số an toàn
        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password", $hashed_password);

        return $stmt->execute();
    }

    // --- 2. Đăng nhập và Xác thực ---
    public function login() {
        $query = "SELECT id, username, password, role, is_approved FROM " . $this->table_name . " WHERE username = :username LIMIT 0,1";
        
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":username", $this->username);
        $stmt->execute();
        $user_row = $stmt->fetch();

        if ($user_row && password_verify($this->password, $user_row->password)) {
            // Kiểm tra trạng thái duyệt
            if ($user_row->is_approved == 0) {
                return "CHUA_DUYET"; // Trả về trạng thái chờ duyệt
            }
            // Đăng nhập thành công và đã được duyệt
            return $user_row; 
        }
        return false; // Sai thông tin đăng nhập
    }

    // --- 3. Phương thức Admin duyệt (Admin Panel) ---
    public function approveUser($user_id) {
        $query = "UPDATE " . $this->table_name . " SET is_approved = 1 WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $user_id);
        return $stmt->execute();
    }
}
?>