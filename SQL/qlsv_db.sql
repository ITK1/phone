-- 1. TẠO CƠ SỞ DỮ LIỆU
CREATE DATABASE IF NOT EXISTS qlsv_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Chọn CSDL để sử dụng
USE qlsv_db;

-- 2. BẢNG NGƯỜI DÙNG (USER)
-- Lưu trữ thông tin đăng nhập, vai trò (admin, teacher) và trạng thái duyệt
CREATE TABLE IF NOT EXISTS user (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL, -- Cần lưu trữ mật khẩu đã HASH
    role ENUM('admin', 'teacher') NOT NULL DEFAULT 'teacher',
    is_approved BOOLEAN NOT NULL DEFAULT 0, -- 0: Chờ duyệt, 1: Đã duyệt
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- 3. THÊM TÀI KHOẢN ADMIN MẶC ĐỊNH
-- Mật khẩu: 'admin123' (Hãy nhớ HASH mật khẩu này trong code PHP của bạn trước khi INSERT)
-- Ví dụ: Giả sử 'admin123' sau khi hash là '$2y$10$abcdefghijklmnopqrstuvwxyz...'
INSERT INTO user (username, password, role, is_approved) VALUES 
('admin', '$2y$10$iM.F3gNl0E7l0Y/r6O00U.z/Fk9Q0IuS/oJ1A3C4D5E6F7G8H9I0', 'admin', 1); 
-- *LƯU Ý QUAN TRỌNG: Bạn phải tạo HASH mật khẩu bằng PHP (password_hash) rồi thay vào đây.*