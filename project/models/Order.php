<?php
class Order {
    private $conn;
    private $table = "orders";

    public $service_id;
    public $customer_name;
    public $contact_info;
    public $acc_username; // MỚI
    public $acc_password; // MỚI
    public $message;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create() {
        // Cập nhật câu truy vấn thêm acc_username và acc_password
        $query = "INSERT INTO " . $this->table . " 
                  SET service_id=:sid, customer_name=:name, contact_info=:contact, 
                      acc_username=:user, acc_password=:pass, message=:msg";
        
        $stmt = $this->conn->prepare($query);

        // Làm sạch dữ liệu
        $this->customer_name = htmlspecialchars(strip_tags($this->customer_name));
        $this->contact_info = htmlspecialchars(strip_tags($this->contact_info));
        $this->acc_username = htmlspecialchars(strip_tags($this->acc_username));
        $this->acc_password = htmlspecialchars(strip_tags($this->acc_password));
        $this->message = htmlspecialchars(strip_tags($this->message));

        // Gán dữ liệu
        $stmt->bindParam(":sid", $this->service_id);
        $stmt->bindParam(":name", $this->customer_name);
        $stmt->bindParam(":contact", $this->contact_info);
        $stmt->bindParam(":user", $this->acc_username); // MỚI
        $stmt->bindParam(":pass", $this->acc_password); // MỚI
        $stmt->bindParam(":msg", $this->message);

        return $stmt->execute();
    }
}
?>