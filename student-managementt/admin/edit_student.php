<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$pageTitle = 'Chỉnh sửa Sinh viên';

$studentId = intval($_GET['id'] ?? 0);
if (!$studentId) {
    header('Location: students.php');
    exit();
}

// Get student info
$student = fetchSingle("SELECT * FROM students WHERE id = ?", [$studentId]);
if (!$student) {
    $_SESSION['error_message'] = 'Không tìm thấy sinh viên.';
    header('Location: students.php');
    exit();
}

// Get user account
$user = fetchSingle("SELECT * FROM users WHERE student_id = ?", [$studentId]);

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $studentCode = trim($_POST['student_code'] ?? '');
    $fullName = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $dateOfBirth = $_POST['date_of_birth'] ?? '';
    $className = trim($_POST['class_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    // Validation
    if (empty($studentCode)) {
        $errors[] = 'Mã sinh viên không được để trống.';
    } elseif ($studentCode !== $student['student_code'] && studentCodeExists($studentCode)) {
        $errors[] = 'Mã sinh viên đã tồn tại.';
    }
    
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
    
    if (empty($username)) {
        $errors[] = 'Tên đăng nhập không được để trống.';
    } elseif ($username !== $user['username'] && usernameExists($username)) {
        $errors[] = 'Tên đăng nhập đã tồn tại.';
    }
    
    if (!empty($password) && !validatePassword($password)) {
        $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
    }
    
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();
            
            // Update student
            $sql = "UPDATE students SET student_code = ?, full_name = ?, email = ?, phone = ?, 
                    address = ?, date_of_birth = ?, class_name = ? WHERE id = ?";
            executeQuery($sql, [$studentCode, $fullName, $email, $phone, $address, 
                               $dateOfBirth ?: null, $className, $studentId]);
            
            // Update user account
            if (!empty($password)) {
                $sql = "UPDATE users SET username = ?, password = ? WHERE student_id = ?";
                executeQuery($sql, [$username, hashPassword($password), $studentId]);
            } else {
                $sql = "UPDATE users SET username = ? WHERE student_id = ?";
                executeQuery($sql, [$username, $studentId]);
            }
            
            $pdo->commit();
            
            $_SESSION['success_message'] = 'Cập nhật thông tin sinh viên thành công!';
            header('Location: students.php');
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
                <i class="fas fa-user-edit me-2"></i>Chỉnh sửa sinh viên
            </h1>
            <a href="students.php" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle me-2"></i>Thông tin sinh viên
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
                                <label for="student_code" class="form-label">Mã sinh viên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="student_code" name="student_code" 
                                       value="<?php echo htmlspecialchars($_POST['student_code'] ?? $student['student_code']); ?>" required>
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
                        <i class="fas fa-key me-2"></i>Thông tin đăng nhập
                    </h6>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="username" class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" 
                                       value="<?php echo htmlspecialchars($_POST['username'] ?? $user['username']); ?>" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="password" class="form-label">Mật khẩu mới</label>
                                <input type="password" class="form-control" id="password" name="password">
                                <div class="form-text">Để trống nếu không muốn thay đổi mật khẩu.</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end gap-2">
                        <a href="students.php" class="btn btn-secondary">
                            <i class="fas fa-times me-2"></i>Hủy
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Cập nhật
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
                    <i class="fas fa-info-circle me-2"></i>Thông tin hiện tại
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2">
                        <strong>Mã SV:</strong> <?php echo htmlspecialchars($student['student_code']); ?>
                    </li>
                    <li class="mb-2">
                        <strong>Tên đăng nhập:</strong> <?php echo htmlspecialchars($user['username']); ?>
                    </li>
                    <li class="mb-2">
                        <strong>Ngày tạo:</strong> <?php echo date('d/m/Y H:i', strtotime($student['created_at'])); ?>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
