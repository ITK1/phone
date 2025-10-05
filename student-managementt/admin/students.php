<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$pageTitle = 'Quản lý Sinh viên';

// Handle search
$search = $_GET['search'] ?? '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = "WHERE full_name LIKE ? OR student_code LIKE ? OR email LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

// Get students with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT * FROM students $whereClause ORDER BY created_at DESC LIMIT $limit OFFSET $offset";
$students = fetchAll($sql, $params);

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM students $whereClause";
$totalStudents = fetchSingle($countSql, $params)['total'];
$totalPages = ceil($totalStudents / $limit);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-users me-2"></i>Quản lý Sinh viên
            </h1>
            <a href="add_student.php" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Thêm sinh viên
            </a>
        </div>
    </div>
</div>

<!-- Search and Filter -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-8">
                <div class="input-group">
                    <span class="input-group-text">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo htmlspecialchars($search); ?>" 
                           placeholder="Tìm kiếm theo tên, mã sinh viên hoặc email...">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Tìm kiếm
                </button>
                <a href="students.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Students Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Danh sách sinh viên 
            <span class="badge bg-primary"><?php echo $totalStudents; ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($students)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không tìm thấy sinh viên nào.</p>
                <a href="add_student.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Thêm sinh viên đầu tiên
                </a>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã SV</th>
                            <th>Họ và tên</th>
                            <th>Email</th>
                            <th>Số điện thoại</th>
                            <th>Lớp</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($student['student_code']); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($student['full_name']); ?></div>
                                    <?php if ($student['date_of_birth']): ?>
                                        <small class="text-muted"><?php echo date('d/m/Y', strtotime($student['date_of_birth'])); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <a href="mailto:<?php echo htmlspecialchars($student['email']); ?>" class="text-decoration-none">
                                        <?php echo htmlspecialchars($student['email']); ?>
                                    </a>
                                </td>
                                <td><?php echo htmlspecialchars($student['phone'] ?: '-'); ?></td>
                                <td>
                                    <?php if ($student['class_name']): ?>
                                        <span class="badge bg-info"><?php echo htmlspecialchars($student['class_name']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($student['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="edit_student.php?id=<?php echo $student['id']; ?>" 
                                           class="btn btn-sm btn-outline-primary" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="delete_student.php?id=<?php echo $student['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa sinh viên này?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <nav aria-label="Page navigation">
                <ul class="pagination justify-content-center mb-0">
                    <?php if ($page > 1): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>
