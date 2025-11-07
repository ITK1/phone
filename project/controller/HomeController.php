<?php
// Sử dụng đường dẫn tuyệt đối với __DIR__ để tránh lỗi khi include từ các vị trí khác nhau
include_once __DIR__ . '/../config/config.php';
include_once __DIR__ . '/../core/connect.php';
include_once __DIR__ . '/../models/Service.php';

class HomeController {
    
    public function index() {
        // 1. Khởi tạo kết nối CSDL (Singleton pattern)
        try {
            $db = Database::getsql()->getConnection();
        } catch (Exception $e) {
            // Xử lý lỗi kết nối nếu cần thiết (ví dụ: ghi log, hiển thị trang bảo trì)
            die("Lỗi kết nối cơ sở dữ liệu.");
        }
        
        // 2. Lấy dữ liệu từ Model
        $serviceModel = new Service($db);
        $stmt = $serviceModel->readAll();
        $services = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // 3. Render View
        // Truyền biến $services vào view để sử dụng
        $this->render('home/index', ['services' => $services]);
    }

    /**
     * Hàm hỗ trợ render view để code gọn hơn
     * @param string $viewPath Tên file view (không cần đuôi .php)
     * @param array $data Mảng dữ liệu truyền vào view
     */
    private function render($viewPath, $data = []) {
        // Giải nén mảng data thành các biến riêng lẻ để view sử dụng
        extract($data);

        // Đường dẫn thư mục view
        $viewDir = __DIR__ . '/../views/';

        // Include các thành phần giao diện
        include_once $viewDir . './layouts/header.php';
        include_once $viewDir . $viewPath . '.php';
        include_once $viewDir . './layouts/footer.php';
    }
}
?>