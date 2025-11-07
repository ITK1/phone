<?php
include_once './core/connect.php';
include_once './models/Service.php';
include_once 'models/Order.php';

class OrderController {
    private $db;

    public function __construct() {
        // Gọi kết nối qua Singleton
        $this->db = Database::getsql()->getConnection();
    }

    public function create() {
        $service_id = isset($_GET['id']) ? $_GET['id'] : die('LỖI: Thiếu ID dịch vụ');
        $service = new Service($this->db);
        $service->id = $service_id;
        if(!$service->readOne()) die('LỖI: Dịch vụ không tồn tại');

        include 'views/layouts/header.php';
        include 'views/order/create.php';
        include 'views/layouts/footer.php';
    }

    public function store() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $order = new Order($this->db);
            $order->service_id = $_POST['service_id'];
            $order->customer_name = $_POST['fullname'];
            $order->contact_info = $_POST['contact'];
            
            // NHẬN DỮ LIỆU MỚI
            $order->acc_username = $_POST['acc_username'];
            $order->acc_password = $_POST['acc_password'];
            
            $order->message = $_POST['message'];

            if($order->create()) {
                header("Location: index.php?page=order_success");
            } else {
                die("Lỗi hệ thống, vui lòng thử lại.");
            }
        }
    }

    public function success() {
        include 'views/layouts/header.php';
        include 'views/order/success.php';
        include 'views/layouts/footer.php';
    }
}
?>