<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireStudent();

$pageTitle = 'Thông tin cá nhân';

$user = getCurrentUser();
$studentId = $_SESSION['student_id'];

// Get student details
$student = fetchSingle("SELECT * FROM students WHERE id = ?", [$studentId]);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dateOfBirth = $_POST['date_of_birth'] ?? '';
    $className = trim($_POST['class_name'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validation
    if (empty($fullName)) {
        $errors[] = 'Họ và tên không được để trống.';
    }
    
    if (empty($email)) {
        $errors[] = 'Email không được để trống.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Email không hợp lệ.';
    } elseif ($email !== $student['email'] && emailExists($email)) {
        $errors[] = 'Email đã tồn tại.';
    }
    
    if (!empty($newPassword)) {
        if (empty($currentPassword)) {
            $errors[] = 'Vui lòng nhập mật khẩu hiện tại.';
        } else {
            // Verify current password
            $userAccount = fetchSingle("SELECT password FROM users WHERE student_id = ?", [$studentId]);
            if (!password_verify($currentPassword, $userAccount['password'])) {
                $errors[] = 'Mật khẩu hiện tại không chính xác.';
            } elseif (!validatePassword($newPassword)) {
                $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            } elseif ($newPassword !== $confirmPassword) {
                $errors[] = 'Mật khẩu xác nhận không khớp.';
            }
        }
    }
    
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Update student info
            $sql = "UPDATE students SET full_name = ?, email = ?, phone = ?, address = ?, 
                    date_of_birth = ?, class_name = ? WHERE id = ?";
            executeQuery($sql, [$fullName, $email, $phone, $address, $dateOfBirth ?: null, $className, $studentId]);
            
            // Update password if provided
            if (!empty($newPassword)) {
                $sql = "UPDATE users SET password = ? WHERE student_id = ?";
                executeQuery($sql, [hashPassword($newPassword), $studentId]);
            }
            
            $pdo->commit();
            
            // Update session data
            $_SESSION['full_name'] = $fullName;
            
            $_SESSION['success_message'] = 'Cập nhật thông tin thành công!';
            header('Location: profile.php');
            exit();
            
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors[] = 'Có lỗi xảy ra khi cập nhật: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-user me-2"></i>Thông tin cá nhân
            </h1>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-edit me-2"></i>Chỉnh sửa thông tin
                </h5>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo htmlspecialchars($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_code" class="form-label">Mã sinh viên</label>
                                <input type="text" class="form-control" value="<?php echo htmlspecialchars($student['student_code']); ?>" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="full_name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="full_name" name="full_name" 
                                       value="<?php echo htmlspecialchars($_POST['full_name'] ?? $student['full_name']); ?>" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? $student['email']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại</label>
                                <input type="tel" class="form-control" id="phone" name="phone" 
                                       value="<?php echo htmlspecialchars($_POST['phone'] ?? $student['phone']); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">Địa chỉ</label>
                        <textarea class="form-control" id="address" name="address" rows="3"><?php echo htmlspecialchars($_POST['address'] ?? $student['address']); ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="date_of_birth" class="form-label">Ngày sinh</label>
                                <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" 
                                       value="<?php echo htmlspecialchars($_POST['date_of_birth'] ?? $student['date_of_birth']); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="class_name" class="form-label">Lớp</label>
                                <input type="text" class="form-control" id="class_name" name="class_name" 
                                       value="<?php echo htmlspecialchars($_POST['class_name'] ?? $student['class_name']); ?>">
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">
                    <h6 class="mb-3">
                        <i class="fas fa-key me-2"></i>Thay đổi mật khẩu
                    </h6>

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Mật khẩu hiện tại</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                        <div class="form-text">Chỉ cần nhập nếu muốn thay đổi mật khẩu.</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="new_password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="new_password" name="new_password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="confirm_password" class="form-label">Xác nhận mật khẩu</label>
                                <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin tài khoản
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?>
                    </li>
                    <li class="mb-2">
                        <strong>Mã sinh viên:</strong> <?php echo htmlspecialchars($student['student_code']); ?>
                    </li>
                    <li class="mb-2">
                        <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($student['created_at'])); ?>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-bar me-2"></i>Thống kê học tập
                </h5>
            </div>
            <div class="card-body">
                <?php
                // Get student statistics
                $enrolledCourses = fetchSingle("SELECT COUNT(*) as count FROM student_courses WHERE student_id = ?", [$studentId])['count'];
                $completedCourses = fetchSingle("SELECT COUNT(*) as count FROM scores WHERE student_id = ? AND total_score IS NOT NULL", [$studentId])['count'];
                $averageScore = fetchSingle("SELECT AVG(total_score) as avg FROM scores WHERE student_id = ? AND total_score IS NOT NULL", [$studentId])['avg'];
                ?>
                
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Khóa học đã đăng ký:</strong> 
                        <span class="badge bg-primary"><?php echo $enrolledCourses; ?></span>
                    </li>
                    <li class="mb-2">
                        <strong>Khóa học đã hoàn thành:</strong> 
                        <span class="badge bg-success"><?php echo $completedCourses; ?></span>
                    </li>
                    <li class="mb-2">
                        <strong>Điểm trung bình:</strong> 
                        <span class="badge bg-warning"><?php echo $averageScore ? number_format($averageScore, 1) : '0'; ?></span>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
