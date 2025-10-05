-- Student Management System Database
CREATE DATABASE IF NOT EXISTS student_management;
USE student_management;

-- Users table (for authentication)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student') NOT NULL,
    student_id INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
);

-- Students table
CREATE TABLE students (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_code VARCHAR(20) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(15),
    address TEXT,
    date_of_birth DATE,
    class_name VARCHAR(50),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Courses table
CREATE TABLE courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    course_code VARCHAR(20) UNIQUE NOT NULL,
    course_name VARCHAR(100) NOT NULL,
    credits INT NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Student_Courses table (many-to-many relationship)
CREATE TABLE student_courses (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    enrolled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_enrollment (student_id, course_id)
);

-- Scores table
CREATE TABLE scores (
    id INT PRIMARY KEY AUTO_INCREMENT,
    student_id INT NOT NULL,
    course_id INT NOT NULL,
    midterm_score DECIMAL(5,2),
    final_score DECIMAL(5,2),
    assignment_score DECIMAL(5,2),
    total_score DECIMAL(5,2),
    grade VARCHAR(2),
    semester VARCHAR(20),
    academic_year VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    UNIQUE KEY unique_score (student_id, course_id, semester, academic_year)
);

-- Insert sample data
INSERT INTO students (student_code, full_name, email, phone, address, date_of_birth, class_name) VALUES
('SV001', 'Nguyễn Văn An', 'an.nguyen@email.com', '0123456789', 'Hà Nội', '2000-01-15', 'CNTT2020'),
('SV002', 'Trần Thị Bình', 'binh.tran@email.com', '0987654321', 'TP.HCM', '2000-03-20', 'CNTT2020'),
('SV003', 'Lê Văn Cường', 'cuong.le@email.com', '0369258147', 'Đà Nẵng', '1999-12-10', 'CNTT2019');

INSERT INTO courses (course_code, course_name, credits, description) VALUES
('CS101', 'Lập trình Cơ bản', 3, 'Khóa học lập trình cơ bản với C++'),
('CS102', 'Cấu trúc dữ liệu', 4, 'Học về các cấu trúc dữ liệu cơ bản'),
('CS103', 'Cơ sở dữ liệu', 3, 'Học về thiết kế và quản lý CSDL'),
('CS104', 'Lập trình Web', 4, 'HTML, CSS, JavaScript và PHP');

INSERT INTO users (username, password, role, student_id) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', NULL),
('SV001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 1),
('SV002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 2),
('SV003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'student', 3);

-- Insert sample enrollments
INSERT INTO student_courses (student_id, course_id) VALUES
(1, 1), (1, 2), (1, 3),
(2, 1), (2, 2), (2, 4),
(3, 2), (3, 3), (3, 4);

-- Insert sample scores
INSERT INTO scores (student_id, course_id, midterm_score, final_score, assignment_score, total_score, grade, semester, academic_year) VALUES
(1, 1, 8.5, 9.0, 8.0, 8.5, 'A', 'HK1', '2023-2024'),
(1, 2, 7.5, 8.0, 8.5, 8.0, 'B+', 'HK1', '2023-2024'),
(2, 1, 9.0, 8.5, 9.0, 8.8, 'A', 'HK1', '2023-2024'),
(2, 2, 8.0, 7.5, 8.0, 7.8, 'B', 'HK1', '2023-2024');
