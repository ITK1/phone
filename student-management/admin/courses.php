<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$pageTitle = 'Quản lý Khóa học';

// Handle search
$search = $_GET['search'] ?? '';
$whereClause = '';
$params = [];

if (!empty($search)) {
    $whereClause = "WHERE course_name LIKE ? OR course_code LIKE ? OR description LIKE ?";
    $searchTerm = "%$search%";
    $params = [$searchTerm, $searchTerm, $searchTerm];
}

// Get courses with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 10;
$offset = ($page - 1) * $limit;

$sql = "SELECT c.*, COUNT(sc.student_id) as enrolled_students 
        FROM courses c 
        LEFT JOIN student_courses sc ON c.id = sc.course_id 
        $whereClause 
        GROUP BY c.id 
        ORDER BY c.created_at DESC 
        LIMIT $limit OFFSET $offset";
$courses = fetchAll($sql, $params);

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM courses $whereClause";
$totalCourses = fetchSingle($countSql, $params)['total'];
$totalPages = ceil($totalCourses / $limit);

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-book me-2"></i>Quản lý Khóa học
            </h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                <i class="fas fa-plus me-2"></i>Thêm khóa học
            </button>
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
                           placeholder="Tìm kiếm theo tên khóa học, mã khóa học hoặc mô tả...">
                </div>
            </div>
            <div class="col-md-4">
                <button type="submit" class="btn btn-outline-primary me-2">
                    <i class="fas fa-search me-1"></i>Tìm kiếm
                </button>
                <a href="courses.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times me-1"></i>Xóa bộ lọc
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Courses Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Danh sách khóa học 
            <span class="badge bg-primary"><?php echo $totalCourses; ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không tìm thấy khóa học nào.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                    <i class="fas fa-plus me-2"></i>Thêm khóa học đầu tiên
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã khóa học</th>
                            <th>Tên khóa học</th>
                            <th>Tín chỉ</th>
                            <th>Số SV đăng ký</th>
                            <th>Mô tả</th>
                            <th>Ngày tạo</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courses as $course): ?>
                            <tr>
                                <td>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($course['course_code']); ?></span>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($course['course_name']); ?></div>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $course['credits']; ?> tín chỉ</span>
                                </td>
                                <td>
                                    <span class="badge bg-success"><?php echo $course['enrolled_students']; ?> sinh viên</span>
                                </td>
                                <td>
                                    <?php if ($course['description']): ?>
                                        <span class="text-muted" title="<?php echo htmlspecialchars($course['description']); ?>">
                                            <?php echo htmlspecialchars(substr($course['description'], 0, 50)) . (strlen($course['description']) > 50 ? '...' : ''); ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($course['created_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editCourse(<?php echo htmlspecialchars(json_encode($course)); ?>)" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="delete_course.php?id=<?php echo $course['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa khóa học này?')">
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

<!-- Add Course Modal -->
<div class="modal fade" id="addCourseModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="courseForm" method="POST" action="save_course.php">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Thêm khóa học mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="courseId" name="course_id" value="">
                    
                    <div class="mb-3">
                        <label for="course_code" class="form-label">Mã khóa học <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="course_code" name="course_code" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="course_name" class="form-label">Tên khóa học <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="course_name" name="course_name" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="credits" class="form-label">Số tín chỉ <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="credits" name="credits" min="1" max="10" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Lưu
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCourse(course) {
    document.getElementById('courseId').value = course.id;
    document.getElementById('course_code').value = course.course_code;
    document.getElementById('course_name').value = course.course_name;
    document.getElementById('credits').value = course.credits;
    document.getElementById('description').value = course.description || '';
    
    document.querySelector('#addCourseModal .modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Chỉnh sửa khóa học';
    
    new bootstrap.Modal(document.getElementById('addCourseModal')).show();
}

// Reset form when modal is hidden
document.getElementById('addCourseModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('courseForm').reset();
    document.getElementById('courseId').value = '';
    document.querySelector('#addCourseModal .modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Thêm khóa học mới';
});
</script>

<?php include '../includes/footer.php'; ?>
