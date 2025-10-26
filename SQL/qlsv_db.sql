CREATE DATABASE IF NOT EXISTS qlsv CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE qlsv;

-- ======================
-- 1️⃣ Bảng sinh viên
-- ======================
CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- 2️⃣ Bảng khóa học
-- ======================
CREATE TABLE courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ======================
-- 3️⃣ Bảng lịch học
-- ======================
CREATE TABLE schedules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    day VARCHAR(20) NOT NULL,
    time VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE
);

-- ======================
-- 4️⃣ Thêm dữ liệu mẫu
-- ======================
INSERT INTO students (name, email, phone) VALUES
('Nguyễn Văn A', 'vana@example.com', '0909123456'),
('Trần Thị B', 'thib@example.com', '0909234567');

INSERT INTO courses (name, description) VALUES
('Lập trình PHP cơ bản', 'Khóa học nhập môn PHP và MySQL'),
('Cơ sở dữ liệu MySQL nâng cao', 'Học thiết kế và tối ưu hóa database');

INSERT INTO schedules (student_id, course_id, day, time) VALUES
(1, 1, 'Thứ 2', '08:00'),
(2, 2, 'Thứ 4', '14:00');
