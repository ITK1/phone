<?php
// Định nghĩa thư mục gốc của controller để tiện sử dụng
define('CONTROLLER_PATH', __DIR__ . '/controller/');

// Lấy trang hiện tại, mặc định là 'home'
$page = $_GET['page'] ?? 'home';

// Mảng ánh xạ routing: 'tên_trang' => ['Controller', 'method']
$routes = [
    'home'          => ['HomeController', 'index'],
    'order'         => ['OrderController', 'create'],
    'order_store'   => ['OrderController', 'store'],
    'order_success' => ['OrderController', 'success'],
];

// Kiểm tra xem trang yêu cầu có trong danh sách route không
if (array_key_exists($page, $routes)) {
    [$controllerName, $method] = $routes[$page];
    $controllerFile = CONTROLLER_PATH . $controllerName . '.php';

    // Kiểm tra file controller có tồn tại không trước khi include
    if (file_exists($controllerFile)) {
        include_once $controllerFile;
        
        // Kiểm tra class và method có tồn tại không
        if (class_exists($controllerName) && method_exists($controllerName, $method)) {
            (new $controllerName())->$method();
        } else {
            // Lỗi server nội bộ nếu class/method không tìm thấy dù file tồn tại
            http_response_code(500);
            echo "Error: Controller class or method not found.";
        }
    } else {
        // Lỗi server nếu file controller bị thiếu
        http_response_code(500);
        echo "Error: Controller file not found: " . $controllerName;
    }
} else {
    // Trang không tồn tại trong route
    http_response_code(404);
    // Có thể include một file view 404 đẹp hơn ở đây
    echo "<h1 style='text-align:center; margin-top:50px; font-family:sans-serif;'>404 - Page Not Found</h1>";
}
?>