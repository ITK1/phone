<?php
require_once __DIR__ . '/../models/Course.php';

$courseModel = new Course();
$courses = $courseModel->getAllCourses();

// Xử lý form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_id  = $_POST['course_id'] ?? '';
    $title      = $_POST['title'] ?? '';
    $video_url  = $_POST['video_url'] ?? '';
    $description = $_POST['description'] ?? '';
    $is_demo    = isset($_POST['is_demo']) ? 1 : 0;

    if (!empty($course_id) && !empty($title) && !empty($video_url)) {
        // Chuyển link YouTube sang dạng embed
        if (strpos($video_url, 'youtube.com/watch') !== false) {
            parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
            if (isset($params['v'])) {
                $video_url = "https://www.youtube.com/embed/" . $params['v'];
            }
        }
        $courseModel->addVideo($course_id, $title, $video_url, $description, $is_demo);
        header("Location: add_video.php");
        exit;
    } else {
        $error = "Vui lòng nhập đầy đủ thông tin video.";
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm video cho khóa học</title>
</head>
<body>
    <h2>Thêm video cho khóa học</h2>

    <?php if (!empty($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form action="" method="POST">
        <label>Chọn khóa học:</label><br>
        <select name="course_id" required>
            <option value="">-- Chọn khóa học --</option>
            <?php foreach ($courses as $course): ?>
                <option value="<?= $course['id'] ?>">
                    <?= htmlspecialchars($course['name']) ?> (GV: <?= htmlspecialchars($course['teacher']) ?>)
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label>Tiêu đề video:</label><br>
        <input type="text" name="title" required><br><br>

        <label>Link video YouTube:</label><br>
        <input type="text" name="video_url" required><br><br>

        <label>Mô tả video:</label><br>
        <textarea name="description"></textarea><br><br>

        <label>
            <input type="checkbox" name="is_demo" value="1"> Đây là video học thử (demo)
        </label><br><br>

        <button type="submit">Thêm video</button>
    </form>
</body>
</html>
