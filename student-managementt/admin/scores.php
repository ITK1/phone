<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireAdmin();

$pageTitle = 'Quản lý Điểm số';

// Handle filters
$studentId = intval($_GET['student_id'] ?? 0);
$courseId = intval($_GET['course_id'] ?? 0);
$semester = trim($_GET['semester'] ?? '');
$academicYear = trim($_GET['academic_year'] ?? '');

$whereConditions = [];
$params = [];

if ($studentId) {
    $whereConditions[] = "s.student_id = ?";
    $params[] = $studentId;
}

if ($courseId) {
    $whereConditions[] = "s.course_id = ?";
    $params[] = $courseId;
}

if ($semester) {
    $whereConditions[] = "s.semester = ?";
    $params[] = $semester;
}

if ($academicYear) {
    $whereConditions[] = "s.academic_year = ?";
    $params[] = $academicYear;
}

$whereClause = $whereConditions ? 'WHERE ' . implode(' AND ', $whereConditions) : '';

// Get scores with pagination
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 15;
$offset = ($page - 1) * $limit;

$sql = "SELECT s.*, st.full_name, st.student_code, c.course_name, c.course_code 
        FROM scores s 
        JOIN students st ON s.student_id = st.id 
        JOIN courses c ON s.course_id = c.id 
        $whereClause 
        ORDER BY s.created_at DESC 
        LIMIT $limit OFFSET $offset";
$scores = fetchAll($sql, $params);

// Get total count for pagination
$countSql = "SELECT COUNT(*) as total FROM scores s $whereClause";
$totalScores = fetchSingle($countSql, $params)['total'];
$totalPages = ceil($totalScores / $limit);

// Get filter options
$students = fetchAll("SELECT id, student_code, full_name FROM students ORDER BY student_code");
$courses = fetchAll("SELECT id, course_code, course_name FROM courses ORDER BY course_code");
$semesters = fetchAll("SELECT DISTINCT semester FROM scores WHERE semester IS NOT NULL ORDER BY semester");
$academicYears = fetchAll("SELECT DISTINCT academic_year FROM scores WHERE academic_year IS NOT NULL ORDER BY academic_year DESC");

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-chart-line me-2"></i>Quản lý Điểm số
            </h1>
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScoreModal">
                <i class="fas fa-plus me-2"></i>Thêm điểm
            </button>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>Bộ lọc
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label for="student_id" class="form-label">Sinh viên</label>
                <select class="form-select" id="student_id" name="student_id">
                    <option value="">Tất cả sinh viên</option>
                    <?php foreach ($students as $student): ?>
                        <option value="<?php echo $student['id']; ?>" 
                                <?php echo $studentId == $student['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($student['student_code'] . ' - ' . $student['full_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-3">
                <label for="course_id" class="form-label">Khóa học</label>
                <select class="form-select" id="course_id" name="course_id">
                    <option value="">Tất cả khóa học</option>
                    <?php foreach ($courses as $course): ?>
                        <option value="<?php echo $course['id']; ?>" 
                                <?php echo $courseId == $course['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="semester" class="form-label">Học kỳ</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="">Tất cả</option>
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?php echo htmlspecialchars($sem['semester']); ?>" 
                                <?php echo $semester == $sem['semester'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sem['semester']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label for="academic_year" class="form-label">Năm học</label>
                <select class="form-select" id="academic_year" name="academic_year">
                    <option value="">Tất cả</option>
                    <?php foreach ($academicYears as $year): ?>
                        <option value="<?php echo htmlspecialchars($year['academic_year']); ?>" 
                                <?php echo $academicYear == $year['academic_year'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($year['academic_year']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Lọc
                    </button>
                    <a href="scores.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Xóa
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Scores Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Danh sách điểm số 
            <span class="badge bg-primary"><?php echo $totalScores; ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($scores)): ?>
            <div class="text-center py-5">
                <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                <p class="text-muted">Không tìm thấy điểm số nào.</p>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addScoreModal">
                    <i class="fas fa-plus me-2"></i>Thêm điểm đầu tiên
                </button>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Sinh viên</th>
                            <th>Khóa học</th>
                            <th>Điểm giữa kỳ</th>
                            <th>Điểm cuối kỳ</th>
                            <th>Điểm BT</th>
                            <th>Tổng điểm</th>
                            <th>Xếp loại</th>
                            <th>Học kỳ</th>
                            <th>Năm học</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($scores as $score): ?>
                            <tr>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($score['full_name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($score['student_code']); ?></small>
                                </td>
                                <td>
                                    <div class="fw-bold"><?php echo htmlspecialchars($score['course_name']); ?></div>
                                    <small class="text-muted"><?php echo htmlspecialchars($score['course_code']); ?></small>
                                </td>
                                <td>
                                    <?php if ($score['midterm_score']): ?>
                                        <span class="badge bg-info"><?php echo $score['midterm_score']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($score['final_score']): ?>
                                        <span class="badge bg-info"><?php echo $score['final_score']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($score['assignment_score']): ?>
                                        <span class="badge bg-info"><?php echo $score['assignment_score']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($score['total_score']): ?>
                                        <span class="badge bg-success fs-6"><?php echo $score['total_score']; ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($score['grade']): ?>
                                        <span class="badge bg-primary"><?php echo htmlspecialchars($score['grade']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($score['semester']); ?></span>
                                </td>
                                <td>
                                    <span class="badge bg-secondary"><?php echo htmlspecialchars($score['academic_year']); ?></span>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                onclick="editScore(<?php echo htmlspecialchars(json_encode($score)); ?>)" title="Chỉnh sửa">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="delete_score.php?id=<?php echo $score['id']; ?>" 
                                           class="btn btn-sm btn-outline-danger" 
                                           title="Xóa"
                                           onclick="return confirm('Bạn có chắc chắn muốn xóa điểm này?')">
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
                            <a class="page-link" href="?page=<?php echo $page - 1; ?>&student_id=<?php echo $studentId; ?>&course_id=<?php echo $courseId; ?>&semester=<?php echo urlencode($semester); ?>&academic_year=<?php echo urlencode($academicYear); ?>">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                    
                    <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                        <li class="page-item <?php echo $i == $page ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>&student_id=<?php echo $studentId; ?>&course_id=<?php echo $courseId; ?>&semester=<?php echo urlencode($semester); ?>&academic_year=<?php echo urlencode($academicYear); ?>">
                                <?php echo $i; ?>
                            </a>
                        </li>
                    <?php endfor; ?>
                    
                    <?php if ($page < $totalPages): ?>
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $page + 1; ?>&student_id=<?php echo $studentId; ?>&course_id=<?php echo $courseId; ?>&semester=<?php echo urlencode($semester); ?>&academic_year=<?php echo urlencode($academicYear); ?>">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    <?php endif; ?>
</div>

<!-- Add Score Modal -->
<div class="modal fade" id="addScoreModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="scoreForm" method="POST" action="save_score.php">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Thêm điểm số mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="scoreId" name="score_id" value="">
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="student_id_form" class="form-label">Sinh viên <span class="text-danger">*</span></label>
                                <select class="form-select" id="student_id_form" name="student_id" required>
                                    <option value="">Chọn sinh viên</option>
                                    <?php foreach ($students as $student): ?>
                                        <option value="<?php echo $student['id']; ?>">
                                            <?php echo htmlspecialchars($student['student_code'] . ' - ' . $student['full_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="course_id_form" class="form-label">Khóa học <span class="text-danger">*</span></label>
                                <select class="form-select" id="course_id_form" name="course_id" required>
                                    <option value="">Chọn khóa học</option>
                                    <?php foreach ($courses as $course): ?>
                                        <option value="<?php echo $course['id']; ?>">
                                            <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="midterm_score" class="form-label">Điểm giữa kỳ</label>
                                <input type="number" class="form-control" id="midterm_score" name="midterm_score" 
                                       min="0" max="10" step="0.1">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="final_score" class="form-label">Điểm cuối kỳ</label>
                                <input type="number" class="form-control" id="final_score" name="final_score" 
                                       min="0" max="10" step="0.1">
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="assignment_score" class="form-label">Điểm bài tập</label>
                                <input type="number" class="form-control" id="assignment_score" name="assignment_score" 
                                       min="0" max="10" step="0.1">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="semester_form" class="form-label">Học kỳ <span class="text-danger">*</span></label>
                                <select class="form-select" id="semester_form" name="semester" required>
                                    <option value="">Chọn học kỳ</option>
                                    <option value="HK1">HK1</option>
                                    <option value="HK2">HK2</option>
                                    <option value="HK3">HK3</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="academic_year_form" class="form-label">Năm học <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="academic_year_form" name="academic_year" 
                                       placeholder="2023-2024" required>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="total_score" class="form-label">Tổng điểm</label>
                                <input type="number" class="form-control" id="total_score" name="total_score" 
                                       min="0" max="10" step="0.1" readonly>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="grade" class="form-label">Xếp loại</label>
                                <input type="text" class="form-control" id="grade" name="grade" maxlength="2" readonly>
                            </div>
                        </div>
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
function editScore(score) {
    document.getElementById('scoreId').value = score.id;
    document.getElementById('student_id_form').value = score.student_id;
    document.getElementById('course_id_form').value = score.course_id;
    document.getElementById('midterm_score').value = score.midterm_score || '';
    document.getElementById('final_score').value = score.final_score || '';
    document.getElementById('assignment_score').value = score.assignment_score || '';
    document.getElementById('total_score').value = score.total_score || '';
    document.getElementById('grade').value = score.grade || '';
    document.getElementById('semester_form').value = score.semester || '';
    document.getElementById('academic_year_form').value = score.academic_year || '';
    
    document.querySelector('#addScoreModal .modal-title').innerHTML = '<i class="fas fa-edit me-2"></i>Chỉnh sửa điểm số';
    
    new bootstrap.Modal(document.getElementById('addScoreModal')).show();
}

// Calculate total score and grade
function calculateScore() {
    const midterm = parseFloat(document.getElementById('midterm_score').value) || 0;
    const final = parseFloat(document.getElementById('final_score').value) || 0;
    const assignment = parseFloat(document.getElementById('assignment_score').value) || 0;
    
    // Simple calculation: 30% midterm + 50% final + 20% assignment
    const total = (midterm * 0.3 + final * 0.5 + assignment * 0.2);
    
    document.getElementById('total_score').value = total.toFixed(1);
    
    // Calculate grade
    let grade = '';
    if (total >= 9.0) grade = 'A+';
    else if (total >= 8.5) grade = 'A';
    else if (total >= 8.0) grade = 'B+';
    else if (total >= 7.0) grade = 'B';
    else if (total >= 6.5) grade = 'C+';
    else if (total >= 5.5) grade = 'C';
    else if (total >= 5.0) grade = 'D+';
    else if (total >= 4.0) grade = 'D';
    else if (total > 0) grade = 'F';
    
    document.getElementById('grade').value = grade;
}

// Add event listeners for score calculation
document.getElementById('midterm_score').addEventListener('input', calculateScore);
document.getElementById('final_score').addEventListener('input', calculateScore);
document.getElementById('assignment_score').addEventListener('input', calculateScore);

// Reset form when modal is hidden
document.getElementById('addScoreModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('scoreForm').reset();
    document.getElementById('scoreId').value = '';
    document.querySelector('#addScoreModal .modal-title').innerHTML = '<i class="fas fa-plus me-2"></i>Thêm điểm số mới';
});
</script>

<?php include '../includes/footer.php'; ?>
