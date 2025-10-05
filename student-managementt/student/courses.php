<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireStudent();

$pageTitle = 'Khóa học của tôi';

$user = getCurrentUser();
$studentId = $_SESSION['student_id'];

// Get student's enrolled courses with scores
$courses = fetchAll("
    SELECT c.*, sc.enrolled_at, s.midterm_score, s.final_score, s.assignment_score, 
           s.total_score, s.grade, s.semester, s.academic_year, s.created_at as score_updated_at
    FROM student_courses sc 
    JOIN courses c ON sc.course_id = c.id 
    LEFT JOIN scores s ON sc.student_id = s.student_id AND sc.course_id = s.course_id 
    WHERE sc.student_id = ? 
    ORDER BY sc.enrolled_at DESC
", [$studentId]);

// Get available courses for enrollment
$availableCourses = fetchAll("
    SELECT c.* 
    FROM courses c 
    WHERE c.id NOT IN (
        SELECT course_id FROM student_courses WHERE student_id = ?
    ) 
    ORDER BY c.course_code
", [$studentId]);

$success = '';
$error = '';

// Handle course enrollment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['enroll_course'])) {
    $courseId = intval($_POST['course_id'] ?? 0);
    
    if ($courseId) {
        try {
            // Check if already enrolled
            $existing = fetchSingle("SELECT id FROM student_courses WHERE student_id = ? AND course_id = ?", [$studentId, $courseId]);
            
            if ($existing) {
                $error = 'Bạn đã đăng ký khóa học này rồi.';
            } else {
                // Enroll in course
                executeQuery("INSERT INTO student_courses (student_id, course_id) VALUES (?, ?)", [$studentId, $courseId]);
                $success = 'Đăng ký khóa học thành công!';
                
                // Refresh the page to show updated data
                header('Location: courses.php');
                exit();
            }
        } catch (Exception $e) {
            $error = 'Có lỗi xảy ra khi đăng ký: ' . $e->getMessage();
        }
    }
}

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-book me-2"></i>Khóa học của tôi
            </h1>
            <?php if (!empty($availableCourses)): ?>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
                    <i class="fas fa-plus me-2"></i>Đăng ký khóa học
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php if ($success): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>
        <?php echo htmlspecialchars($success); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <?php echo htmlspecialchars($error); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
<?php endif; ?>

<!-- Enrolled Courses -->
<div class="card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-list me-2"></i>Khóa học đã đăng ký 
            <span class="badge bg-primary"><?php echo count($courses); ?></span>
        </h5>
    </div>
    <div class="card-body p-0">
        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book fa-3x text-muted mb-3"></i>
                <p class="text-muted">Bạn chưa đăng ký khóa học nào.</p>
                <?php if (!empty($availableCourses)): ?>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#enrollModal">
                        <i class="fas fa-plus me-2"></i>Đăng ký khóa học đầu tiên
                    </button>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã khóa học</th>
                            <th>Tên khóa học</th>
                            <th>Tín chỉ</th>
                            <th>Ngày đăng ký</th>
                            <th>Điểm số</th>
                            <th>Xếp loại</th>
                            <th>Học kỳ</th>
                            <th>Năm học</th>
                            <th>Trạng thái</th>
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
                                    <?php if ($course['description']): ?>
                                        <small class="text-muted"><?php echo htmlspecialchars(substr($course['description'], 0, 50)) . (strlen($course['description']) > 50 ? '...' : ''); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-info"><?php echo $course['credits']; ?> tín chỉ</span>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo date('d/m/Y H:i', strtotime($course['enrolled_at'])); ?>
                                    </small>
                                </td>
                                <td>
                                    <?php if ($course['total_score']): ?>
                                        <div class="fw-bold text-success"><?php echo $course['total_score']; ?></div>
                                        <small class="text-muted">
                                            GK: <?php echo $course['midterm_score'] ?: '-'; ?> | 
                                            CK: <?php echo $course['final_score'] ?: '-'; ?> | 
                                            BT: <?php echo $course['assignment_score'] ?: '-'; ?>
                                        </small>
                                    <?php else: ?>
                                        <span class="text-muted">Chưa có điểm</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['grade']): ?>
                                        <span class="badge bg-success"><?php echo htmlspecialchars($course['grade']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['semester']): ?>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($course['semester']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['academic_year']): ?>
                                        <span class="badge bg-secondary"><?php echo htmlspecialchars($course['academic_year']); ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if ($course['total_score']): ?>
                                        <span class="badge bg-success">Đã hoàn thành</span>
                                    <?php else: ?>
                                        <span class="badge bg-warning">Đang học</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Available Courses -->
<?php if (!empty($availableCourses)): ?>
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-plus-circle me-2"></i>Khóa học có thể đăng ký 
            <span class="badge bg-success"><?php echo count($availableCourses); ?></span>
        </h5>
    </div>
    <div class="card-body">
        <div class="row">
            <?php foreach ($availableCourses as $course): ?>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="card h-100">
                        <div class="card-body">
                            <h6 class="card-title">
                                <span class="badge bg-secondary me-2"><?php echo htmlspecialchars($course['course_code']); ?></span>
                                <?php echo htmlspecialchars($course['course_name']); ?>
                            </h6>
                            <p class="card-text">
                                <small class="text-muted">
                                    <i class="fas fa-book me-1"></i><?php echo $course['credits']; ?> tín chỉ
                                </small>
                            </p>
                            <?php if ($course['description']): ?>
                                <p class="card-text">
                                    <small><?php echo htmlspecialchars(substr($course['description'], 0, 100)) . (strlen($course['description']) > 100 ? '...' : ''); ?></small>
                                </p>
                            <?php endif; ?>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-primary btn-sm w-100" 
                                    onclick="enrollInCourse(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['course_name']); ?>')">
                                <i class="fas fa-plus me-1"></i>Đăng ký
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Enroll Modal -->
<div class="modal fade" id="enrollModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-plus me-2"></i>Đăng ký khóa học mới
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="course_id" class="form-label">Chọn khóa học <span class="text-danger">*</span></label>
                        <select class="form-select" id="course_id" name="course_id" required>
                            <option value="">Chọn khóa học...</option>
                            <?php foreach ($availableCourses as $course): ?>
                                <option value="<?php echo $course['id']; ?>">
                                    <?php echo htmlspecialchars($course['course_code'] . ' - ' . $course['course_name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" name="enroll_course" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Đăng ký
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function enrollInCourse(courseId, courseName) {
    document.getElementById('course_id').value = courseId;
    new bootstrap.Modal(document.getElementById('enrollModal')).show();
}
</script>

<?php include '../includes/footer.php'; ?>
