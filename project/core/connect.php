<?php
require_once __DIR__ . "/../config/config.php"; // Dùng __DIR__ để đường dẫn tuyệt đối an toàn hơn

class Database {
    private static $getsql = null;
    private $pdo;

    private function __construct() {
        try {
            // Sử dụng các hằng số đã define ở config.php
            $dns = "mysql:host=" . host . ";dbname=" . db . ";charset=" . charset;
            $this->pdo = new PDO($dns, user, pass);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Kết nối thất bại: " . $e->getMessage());
        }
    }

    public static function getsql() {
        if (self::$getsql == null) {
            self::$getsql = new Database();
        }
        return self::$getsql;
    }

    public function getConnection() {
        return $this->pdo;
    }
}
?>