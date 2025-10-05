# Hệ thống Quản lý Sinh viên

Hệ thống quản lý sinh viên được xây dựng bằng PHP, MySQL với giao diện Bootstrap 5 hiện đại.

## Tính năng chính

### Dành cho Admin
- ✅ Quản lý sinh viên (thêm, sửa, xóa, tìm kiếm)
- ✅ Quản lý khóa học (thêm, sửa, xóa)
- ✅ Quản lý điểm số (nhập điểm, tính điểm trung bình, xếp loại)
- ✅ Dashboard với thống kê tổng quan
- ✅ Phân trang và tìm kiếm nâng cao

### Dành cho Sinh viên
- ✅ Xem thông tin cá nhân
- ✅ Cập nhật thông tin cá nhân
- ✅ Đăng ký khóa học
- ✅ Xem điểm số và thống kê học tập
- ✅ Dashboard cá nhân

### Tính năng chung
- ✅ Hệ thống đăng nhập/đăng xuất bảo mật
- ✅ Giao diện responsive (tương thích mobile)
- ✅ Thông báo và xác nhận trước khi thực hiện hành động
- ✅ Validation dữ liệu đầu vào
- ✅ Pagination và search
- ✅ Giao diện hiện đại với Bootstrap 5

## Cấu trúc thư mục

```
student-management/
│
├── index.php                # Trang chủ / đăng nhập
├── dashboard.php            # Trang chính sau đăng nhập
├── logout.php               # Xử lý đăng xuất
│
├── admin/                   # Trang quản trị
│   ├── students.php         # Quản lý sinh viên
│   ├── courses.php          # Quản lý khóa học
│   ├── scores.php           # Quản lý điểm
│   ├── add_student.php      # Thêm sinh viên
│   ├── edit_student.php     # Sửa thông tin sinh viên
│   ├── delete_student.php   # Xóa sinh viên
│   ├── save_course.php      # Lưu khóa học
│   ├── delete_course.php    # Xóa khóa học
│   ├── save_score.php       # Lưu điểm
│   └── delete_score.php     # Xóa điểm
│
├── student/                 # Trang sinh viên
│   ├── profile.php          # Thông tin cá nhân
│   ├── courses.php          # Khóa học của sinh viên
│   └── scores.php           # Điểm của sinh viên
│
├── includes/                # File include
│   ├── db.php               # Kết nối CSDL
│   ├── auth.php             # Kiểm tra đăng nhập
│   ├── header.php           # Header chung
│   └── footer.php           # Footer chung
│
├── assets/                  # Tài nguyên
│   ├── css/
│   │   └── style.css        # CSS tùy chỉnh
│   ├── js/
│   │   └── main.js          # JavaScript tùy chỉnh
│   └── images/              # Hình ảnh
│
├── database/
│   └── student_db.sql       # File CSDL
│
└── README.md               # Hướng dẫn sử dụng
```

## Cài đặt

### Yêu cầu hệ thống
- PHP 7.4 trở lên
- MySQL 5.7 trở lên hoặc MariaDB 10.2 trở lên
- Web server (Apache/Nginx)
- PDO MySQL extension

### Bước 1: Tải và cài đặt
1. Tải source code về thư mục web server
2. Cấu hình virtual host (nếu cần)

### Bước 2: Cấu hình database
1. Tạo database MySQL mới:
```sql
CREATE DATABASE student_management;
```

2. Import file database:
```bash
mysql -u root -p student_management < database/student_db.sql
```

3. Cập nhật thông tin kết nối trong `includes/db.php`:
```php
$host = 'localhost';        // Host database
$dbname = 'student_management';  // Tên database
$username = 'root';         // Username database
$password = '';             // Password database
```

### Bước 3: Cấu hình web server
- Đảm bảo thư mục `student-management` có quyền đọc/ghi
- Cấu hình document root trỏ đến thư mục chứa project

### Bước 4: Truy cập hệ thống
- Mở trình duyệt và truy cập: `http://localhost/student-management`
- Đăng nhập với tài khoản mặc định:
  - **Admin**: `admin` / `password`
  - **Sinh viên**: `SV001` / `password`

## Hướng dẫn sử dụng

### Đăng nhập
1. Truy cập trang chủ của hệ thống
2. Nhập tên đăng nhập và mật khẩu
3. Nhấn "Đăng nhập"

### Quản lý sinh viên (Admin)
1. Vào menu "Quản lý Sinh viên"
2. Sử dụng tìm kiếm để lọc sinh viên
3. Nhấn "Thêm sinh viên" để tạo mới
4. Nhấn icon "Sửa" để chỉnh sửa
5. Nhấn icon "Xóa" để xóa (có xác nhận)

### Quản lý khóa học (Admin)
1. Vào menu "Quản lý Khóa học"
2. Nhấn "Thêm khóa học" để tạo mới
3. Nhấn icon "Sửa" để chỉnh sửa
4. Nhấn icon "Xóa" để xóa (có xác nhận)

### Quản lý điểm (Admin)
1. Vào menu "Quản lý Điểm"
2. Sử dụng bộ lọc để tìm điểm cần nhập
3. Nhấn "Thêm điểm" để nhập điểm mới
4. Hệ thống tự động tính điểm trung bình và xếp loại

### Sinh viên
1. **Thông tin cá nhân**: Xem và cập nhật thông tin
2. **Khóa học**: Xem danh sách khóa học đã đăng ký, đăng ký khóa học mới
3. **Điểm số**: Xem điểm chi tiết, thống kê học tập

## Cấu trúc Database

### Bảng `users`
- Lưu thông tin đăng nhập
- Phân quyền admin/student

### Bảng `students`
- Thông tin chi tiết sinh viên
- Mã sinh viên, họ tên, email, số điện thoại, địa chỉ, ngày sinh, lớp

### Bảng `courses`
- Thông tin khóa học
- Mã khóa học, tên, số tín chỉ, mô tả

### Bảng `student_courses`
- Liên kết sinh viên với khóa học (many-to-many)
- Theo dõi ngày đăng ký

### Bảng `scores`
- Điểm số của sinh viên
- Điểm giữa kỳ, cuối kỳ, bài tập, tổng điểm, xếp loại
- Học kỳ và năm học

## Bảo mật

- ✅ Password được hash bằng `password_hash()`
- ✅ Prepared statements chống SQL injection
- ✅ Session management
- ✅ Input validation và sanitization
- ✅ CSRF protection (có thể thêm)
- ✅ File upload validation (nếu có)

## Tùy chỉnh

### Thay đổi giao diện
- Chỉnh sửa file `assets/css/style.css`
- Sử dụng Bootstrap 5 classes

### Thêm tính năng
- Tạo file PHP mới trong thư mục tương ứng
- Include `auth.php` để kiểm tra quyền
- Sử dụng các function helper trong `db.php`

### Cấu hình
- Database: `includes/db.php`
- Authentication: `includes/auth.php`
- UI Components: `includes/header.php`, `includes/footer.php`

## Xử lý lỗi

### Lỗi kết nối database
- Kiểm tra thông tin trong `includes/db.php`
- Đảm bảo MySQL service đang chạy

### Lỗi permission
- Kiểm tra quyền thư mục (755 cho folder, 644 cho file)
- Đảm bảo web server có quyền đọc/ghi

### Lỗi session
- Kiểm tra cấu hình PHP session
- Đảm bảo thư mục session có quyền ghi

## Phát triển thêm

### Tính năng có thể thêm
- [ ] Quản lý lớp học
- [ ] Báo cáo thống kê
- [ ] Export/Import dữ liệu
- [ ] Gửi email thông báo
- [ ] Upload ảnh đại diện
- [ ] API REST
- [ ] Mobile app

### Cải tiến kỹ thuật
- [ ] Composer autoload
- [ ] MVC pattern
- [ ] ORM (Eloquent, Doctrine)
- [ ] Unit testing
- [ ] Docker deployment
- [ ] CI/CD pipeline

## Hỗ trợ

Nếu gặp vấn đề trong quá trình cài đặt hoặc sử dụng, vui lòng:
1. Kiểm tra log lỗi của web server
2. Kiểm tra log lỗi PHP
3. Kiểm tra log lỗi MySQL
4. Đảm bảo đã cài đặt đúng các yêu cầu hệ thống

## License

Dự án này được phát hành dưới MIT License.

## Phiên bản

- **Version**: 1.0.0
- **Release Date**: 2024
- **PHP Version**: 7.4+
- **Bootstrap Version**: 5.1.3
