<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';

requireStudent();

$pageTitle = 'Điểm số của tôi';

$user = getCurrentUser();
$studentId = $_SESSION['student_id'];

// Handle filters
$semester = trim($_GET['semester'] ?? '');
$academicYear = trim($_GET['academic_year'] ?? '');

$whereConditions = ["s.student_id = ?"];
$params = [$studentId];

if ($semester) {
    $whereConditions[] = "s.semester = ?";
    $params[] = $semester;
}

if ($academicYear) {
    $whereConditions[] = "s.academic_year = ?";
    $params[] = $academicYear;
}

$whereClause = 'WHERE ' . implode(' AND ', $whereConditions);

// Get scores
$scores = fetchAll("
    SELECT s.*, c.course_name, c.course_code, c.credits 
    FROM scores s 
    JOIN courses c ON s.course_id = c.id 
    $whereClause 
    ORDER BY s.academic_year DESC, s.semester DESC, c.course_code
", $params);

// Get filter options
$semesters = fetchAll("SELECT DISTINCT semester FROM scores WHERE student_id = ? AND semester IS NOT NULL ORDER BY semester", [$studentId]);
$academicYears = fetchAll("SELECT DISTINCT academic_year FROM scores WHERE student_id = ? AND academic_year IS NOT NULL ORDER BY academic_year DESC", [$studentId]);

// Calculate statistics
$totalCourses = count($scores);
$completedCourses = count(array_filter($scores, function($score) { return $score['total_score'] !== null; }));
$averageScore = $completedCourses > 0 ? array_sum(array_column(array_filter($scores, function($score) { return $score['total_score'] !== null; }), 'total_score')) / $completedCourses : 0;

// Grade distribution
$gradeDistribution = [];
foreach ($scores as $score) {
    if ($score['grade']) {
        $gradeDistribution[$score['grade']] = ($gradeDistribution[$score['grade']] ?? 0) + 1;
    }
}

include '../includes/header.php';
?>

<div class="row">
    <div class="col-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-dark">
                <i class="fas fa-chart-line me-2"></i>Điểm số của tôi
            </h1>
        </div>
    </div>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
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
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title"><?php echo $completedCourses; ?></h4>
                        <p class="card-text">Đã hoàn thành</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-check-circle fa-2x"></i>
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
                        <h4 class="card-title"><?php echo number_format($averageScore, 1); ?></h4>
                        <p class="card-text">Điểm trung bình</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-chart-line fa-2x"></i>
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
                        <h4 class="card-title"><?php echo $completedCourses > 0 ? round(($completedCourses / $totalCourses) * 100) : 0; ?>%</h4>
                        <p class="card-text">Tỷ lệ hoàn thành</p>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-percentage fa-2x"></i>
                    </div>
                </div>
            </div>
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
            <div class="col-md-4">
                <label for="semester" class="form-label">Học kỳ</label>
                <select class="form-select" id="semester" name="semester">
                    <option value="">Tất cả học kỳ</option>
                    <?php foreach ($semesters as $sem): ?>
                        <option value="<?php echo htmlspecialchars($sem['semester']); ?>" 
                                <?php echo $semester == $sem['semester'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($sem['semester']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label for="academic_year" class="form-label">Năm học</label>
                <select class="form-select" id="academic_year" name="academic_year">
                    <option value="">Tất cả năm học</option>
                    <?php foreach ($academicYears as $year): ?>
                        <option value="<?php echo htmlspecialchars($year['academic_year']); ?>" 
                                <?php echo $academicYear == $year['academic_year'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($year['academic_year']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="col-md-4">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="fas fa-search me-1"></i>Lọc
                    </button>
                    <a href="scores.php" class="btn btn-outline-secondary">
                        <i class="fas fa-times me-1"></i>Xóa bộ lọc
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="row">
    <!-- Scores Table -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-list me-2"></i>Chi tiết điểm số 
                    <span class="badge bg-primary"><?php echo count($scores); ?></span>
                </h5>
            </div>
            <div class="card-body p-0">
                <?php if (empty($scores)): ?>
                    <div class="text-center py-5">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Không có điểm số nào.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Khóa học</th>
                                    <th>Điểm giữa kỳ</th>
                                    <th>Điểm cuối kỳ</th>
                                    <th>Điểm BT</th>
                                    <th>Tổng điểm</th>
                                    <th>Xếp loại</th>
                                    <th>Học kỳ</th>
                                    <th>Năm học</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($scores as $score): ?>
                                    <tr>
                                        <td>
                                            <div class="fw-bold"><?php echo htmlspecialchars($score['course_name']); ?></div>
                                            <small class="text-muted"><?php echo htmlspecialchars($score['course_code']); ?> - <?php echo $score['credits']; ?> tín chỉ</small>
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
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <!-- Grade Distribution -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-chart-pie me-2"></i>Phân bố điểm
                </h5>
            </div>
            <div class="card-body">
                <?php if (empty($gradeDistribution)): ?>
                    <p class="text-muted text-center">Chưa có dữ liệu phân bố điểm.</p>
                <?php else: ?>
                    <?php foreach ($gradeDistribution as $grade => $count): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary"><?php echo htmlspecialchars($grade); ?></span>
                            <span class="fw-bold"><?php echo $count; ?> khóa học</span>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar" style="width: <?php echo ($count / $completedCourses) * 100; ?>%"></div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Recent Scores -->
        <div class="card mt-3">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-clock me-2"></i>Điểm gần đây
                </h5>
            </div>
            <div class="card-body">
                <?php 
                $recentScores = array_slice($scores, 0, 5);
                if (empty($recentScores)): ?>
                    <p class="text-muted text-center">Không có điểm gần đây.</p>
                <?php else: ?>
                    <?php foreach ($recentScores as $score): ?>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <div>
                                <h6 class="mb-1"><?php echo htmlspecialchars($score['course_name']); ?></h6>
                                <small class="text-muted"><?php echo htmlspecialchars($score['semester'] . ' ' . $score['academic_year']); ?></small>
                            </div>
                            <div class="text-end">
                                <?php if ($score['total_score']): ?>
                                    <span class="badge bg-success"><?php echo $score['total_score']; ?></span>
                                    <div><small class="text-muted"><?php echo htmlspecialchars($score['grade']); ?></small></div>
                                <?php else: ?>
                                    <span class="text-muted">Chưa có điểm</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($score !== end($recentScores)): ?>
                            <hr class="my-2">
                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>
