-- ========================================
-- TẠO DATABASE
-- ========================================
CREATE DATABASE IF NOT EXISTS qlsv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qlsv;

-- ========================================
-- 1️⃣ BẢNG SINH VIÊN
-- ========================================
DROP TABLE IF EXISTS students;
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- 2️⃣ BẢNG KHÓA HỌC (ĐÃ CÓ: teacher, price, day, time)
-- ========================================
DROP TABLE IF EXISTS courses;
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    teacher VARCHAR(100) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) DEFAULT 0,
    day VARCHAR(20) DEFAULT NULL,
    time VARCHAR(20) DEFAULT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- 3️⃣ BẢNG GHI DANH (ENROLLMENTS)
-- ========================================
DROP TABLE IF EXISTS enrollments;
CREATE TABLE enrollments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  course_id INT NOT NULL,
  student_name VARCHAR(100) NOT NULL,
  student_email VARCHAR(100) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ========================================
-- 4️⃣ BẢNG THÀNH VIÊN (MEMBERSHIPS)
-- ========================================
DROP TABLE IF EXISTS memberships;
CREATE TABLE memberships (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(100) NOT NULL,
  plan VARCHAR(50) NOT NULL,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- ========================================
-- 5️⃣ DỮ LIỆU MẪU
-- ========================================

-- Sinh viên
INSERT INTO students (name, email, phone) VALUES
('Nguyễn Văn A', 'vana@example.com', '0909123456'),
('Trần Thị B', 'thib@example.com', '0909234567');

-- Khóa học (CÓ day & time)
INSERT INTO courses (name, teacher, description, price, day, time) VALUES
('Lập trình PHP cơ bản', 'Nguyễn Văn A', 'Khóa học giúp bạn nắm vững kiến thức PHP từ cơ bản đến nâng cao.', 400000, 'Thứ 2', '08:00'),
('Thiết kế Web với HTML & CSS', 'Trần Thị B', 'Tìm hiểu cách tạo website đẹp, chuẩn SEO và responsive.', 350000, 'Thứ 4', '14:00'),
('Cơ bản về Cấu trúc dữ liệu', 'Lê Hoàng C', 'Khóa học nhập môn CTDL & GT cho sinh viên CNTT.', 300000, 'Thứ 6', '09:30');

-- Ghi danh
INSERT INTO enrollments (course_id, student_name, student_email) VALUES
(1, 'Nguyễn Văn A', 'vana@example.com'),
(2, 'Trần Thị B', 'thib@example.com');

-- Thành viên
INSERT INTO memberships (name, email, plan) VALUES
('Nguyễn Văn A', 'vana@example.com', 'Premium'),
('Trần Thị B', 'thib@example.com', 'Basic');
