<?php
require_once 'includes/auth.php';
require_once 'includes/db.php';

requireLogin();

$pageTitle = 'Dashboard - Hệ thống Quản lý Sinh viên';
$user = getCurrentUser();

// Get statistics based on user role
if (isAdmin()) {
    // Admin statistics
    $totalStudents = fetchSingle("SELECT COUNT(*) as count FROM students")['count'];
    $totalCourses = fetchSingle("SELECT COUNT(*) as count FROM courses")['count'];
    $totalEnrollments = fetchSingle("SELECT COUNT(*) as count FROM student_courses")['count'];
    $averageScore = fetchSingle("SELECT AVG(total_score) as avg FROM scores WHERE total_score IS NOT NULL")['avg'];
    
    // Recent students
    $recentStudents = fetchAll("SELECT * FROM students ORDER BY created_at DESC LIMIT 5");
    
    // Recent enrollments
    $recentEnrollments = fetchAll("
        SELECT sc.*, s.full_name, s.student_code, c.course_name 
        FROM student_courses sc 
        JOIN students s ON sc.student_id = s.id 
        JOIN courses c ON sc.course_id = c.id 
        ORDER BY sc.enrolled_at DESC LIMIT 5
    ");
} else {
    // Student statistics
    $studentId = $_SESSION['student_id'];
    
    $enrolledCourses = fetchSingle("SELECT COUNT(*) as count FROM student_courses WHERE student_id = ?", [$studentId])['count'];
    $completedCourses = fetchSingle("SELECT COUNT(*) as count FROM scores WHERE student_id = ? AND total_score IS NOT NULL", [$studentId])['count'];
    $averageScore = fetchSingle("SELECT AVG(total_score) as avg FROM scores WHERE student_id = ? AND total_score IS NOT NULL", [$studentId])['avg'];
    
    // Student's courses
    $studentCourses = fetchAll("
        SELECT c.*, sc.enrolled_at, s.total_score, s.grade 
        FROM student_courses sc 
        JOIN courses c ON sc.course_id = c.id 
        LEFT JOIN scores s ON sc.student_id = s.student_id AND sc.course_id = s.course_id 
        WHERE sc.student_id = ? 
        ORDER BY sc.enrolled_at DESC
    ", [$studentId]);
    
    // Recent scores
    $recentScores = fetchAll("
        SELECT s.*, c.course_name 
        FROM scores s 
        JOIN courses c ON s.course_id = c.id 
        WHERE s.student_id = ? 
        ORDER BY s.created_at DESC LIMIT 5
    ", [$studentId]);
}

include 'includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-tachometer-alt me-2"></i>
                Dashboard
            </h1>
            <div class="text-muted">
                Xin chào, <strong><?php echo htmlspecialchars($user['full_name'] ?? $user['username']); ?></strong>
            </div>
        </div>
    </div>
</div>

<?php if (isAdmin()): ?>
<!-- Admin Dashboard -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $totalStudents; ?></h4>
                        <p class="card-text">Tổng sinh viên</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $totalCourses; ?></h4>
                        <p class="card-text">Tổng khóa học</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $totalEnrollments; ?></h4>
                        <p class="card-text">Tổng đăng ký</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-clipboard-list fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $averageScore ? number_format($averageScore, 1) : '0'; ?></h4>
                        <p class="card-text">Điểm TB</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>Sinh viên mới nhất
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentStudents)): ?>
                    <p class="text-muted">Chưa có sinh viên nào.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentStudents as $student): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($student['full_name']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($student['student_code']); ?></small>
                                </div>
                                <span class="badge bg-primary rounded-pill"><?php echo htmlspecialchars($student['class_name']); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clipboard-list me-2"></i>Đăng ký mới nhất
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentEnrollments)): ?>
                    <p class="text-muted">Chưa có đăng ký nào.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentEnrollments as $enrollment): ?>
                            <div class="list-group-item">
                                <h6 class="mb-1"><?php echo htmlspecialchars($enrollment['full_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($enrollment['course_name']); ?></small>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php else: ?>
<!-- Student Dashboard -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $enrolledCourses; ?></h4>
                        <p class="card-text">Khóa học đã đăng ký</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-book fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $completedCourses; ?></h4>
                        <p class="card-text">Khóa học đã hoàn thành</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $averageScore ? number_format($averageScore, 1) : '0'; ?></h4>
                        <p class="card-text">Điểm trung bình</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-book me-2"></i>Khóa học của tôi
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($studentCourses)): ?>
                    <p class="text-muted">Bạn chưa đăng ký khóa học nào.</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Mã khóa học</th>
                                    <th>Tên khóa học</th>
                                    <th>Tín chỉ</th>
                                    <th>Điểm</th>
                                    <th>Xếp loại</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($studentCourses as $course): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                                        <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                        <td><?php echo $course['credits']; ?></td>
                                        <td>
                                            <?php if ($course['total_score']): ?>
                                                <span class="badge bg-success"><?php echo $course['total_score']; ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">Chưa có điểm</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if ($course['grade']): ?>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($course['grade']); ?></span>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
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
    </div>
    
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-line me-2"></i>Điểm gần đây
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentScores)): ?>
                    <p class="text-muted">Chưa có điểm nào.</p>
                <?php else: ?>
                    <div class="list-group list-group-flush">
                        <?php foreach ($recentScores as $score): ?>
                            <div class="list-group-item d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-1"><?php echo htmlspecialchars($score['course_name']); ?></h6>
                                    <small class="text-muted"><?php echo htmlspecialchars($score['semester'] . ' ' . $score['academic_year']); ?></small>
                                </div>
                                <span class="badge bg-success"><?php echo $score['total_score']; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<?php include 'includes/footer.php'; ?>
