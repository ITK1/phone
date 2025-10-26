<?php
class Data {
    private $host = "localhost";
    private $db_name = "qlsv_db";
    private $username = "root";
    private $password = "";
    public $conn;

    // Phương thức kết nối database
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            // Thiết lập chế độ báo lỗi (Exceptions) cho PDO
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Thiết lập chế độ lấy dữ liệu mặc định là OBJECT
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ); 
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Lỗi kết nối CSDL: " . $exception->getMessage();
        }
        return $this->conn;
    }
}
?>