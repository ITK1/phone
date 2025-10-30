<?php
require_once __DIR__ . '/../core/connect.php';

class Student {
    private $conn;

    public function __construct() {
        $this->conn = Database::getsql()->getConnection();
    }

    // ✅ Lấy tất cả sinh viên
    public function getAll() {
        $data = $this->conn->prepare("SELECT * FROM students ORDER BY id DESC");
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    // ✅ Đếm tổng số sinh viên
    public function countStudents() {
        $data = $this->conn->prepare("SELECT COUNT(*) AS total FROM students");
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

    // ✅ Thêm sinh viên (chỉ có name, email, dob)
    public function add($name, $email, $dob) {
        $data = $this->conn->prepare("INSERT INTO students (name, email, phone) VALUES (?, ?, ?)");
        $data->execute([$name, $email, $dob]);
    }

    // ✅ Xóa sinh viên theo ID
    public function delete($id) {
        $data = $this->conn->prepare("DELETE FROM students WHERE id = ?");
        $data->execute([$id]);
    }
}
?>