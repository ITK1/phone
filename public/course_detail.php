<?php
require_once __DIR__ . '/../models/Course.php';

$courseModel = new Course();

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Không có ID khóa học");
}

$id = intval($_GET['id']);
$course = $courseModel->getCourseById($id);
$videos = $courseModel->getVideoCourses($id);
$demoVideo = $courseModel->getDemoVideo($id); // ✅ video học thử
$isRegistered = false; // ✅ sau này bạn thay bằng kiểm tra đăng ký thật

if (!$course) {
    die("Khóa học không tồn tại");
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($course['name']) ?></title>
<link rel="stylesheet" href="../assets/style.css" />
<script src="../assets/js/main.js"></script>


</head>
<body>
<div id="product">
  <div class="header-login">
    <div class="nav-login">
      <div class="nav-dk"><a href="#">Đăng ký</a></div>
      <div class="nav-dn"><a href="#">Đăng nhập</a></div>
    </div>
  </div>

  <div>
    <div class="dk">
    <div class="img">
    <?php if ($demoVideo): ?>
        <?php
            // Lấy link gốc từ SQL
            $video_url = trim($demoVideo['video_url']);

            // Tự động chuyển đổi link sang dạng nhúng hợp lệ
            if (strpos($video_url, 'watch?v=') !== false) {
                $video_url = preg_replace('/watch\?v=/', 'embed/', $video_url);
            } elseif (strpos($video_url, 'youtu.be/') !== false) {
                $video_url = str_replace('youtu.be/', 'www.youtube.com/embed/', $video_url);
            }

            // Nếu có thêm các tham số phía sau, bỏ bớt
            $video_url = strtok($video_url, '&');
        ?>

        <iframe width="100%" height="250"
            src="<?= htmlspecialchars($video_url) ?>"
            title="Video học thử"
            frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen>
        </iframe>
    <?php else: ?>
        <img src="../assets/img/ccc.png" alt="Ảnh khóa học" />
    <?php endif; ?>
</div>


      <div class="free"><?= $course['price'] == 0 ? 'Miễn phí' : number_format($course['price']).' VNĐ' ?></div>
      <div class="btn-dk"><a href="#">ĐĂNG KÝ HỌC</a></div>
      <div class="gioithieu">
        <div class="thoiluong">Trình độ cơ bản</div>
        <div class="thoiluong">Tổng số <span><?= count($videos) ?></span> bài học</div>
        <div class="thoiluong">Thời lượng <span><?= htmlspecialchars($course['time']) ?></span></div>
        <div class="thoiluong">Học mọi lúc, mọi nơi</div>
      </div>
    </div>

    <div class="container">
      <div class="header-text"><?= htmlspecialchars($course['name']) ?></div>
      <div class="content"><?= htmlspecialchars($course['description']) ?></div>

      <div class="text-h4">Nội dung khóa học</div>
      <div class="date">
        <div class="thoiluong"><?= count($videos) ?> <span>bài học</span></div>
        <div class="thoiluong">Thời lượng <span><?= htmlspecialchars($course['time']) ?></span></div>
      </div>

      <div class="course-list">
        <div class="list">
          <?php foreach ($videos as $index => $video): ?>
            <div>
              <?= ($index + 1) . ". " . htmlspecialchars($video['title']) ?>
              <?php if ($isRegistered): ?>
                <a href="<?= htmlspecialchars($video['video_url']) ?>" target="_blank">▶ Xem</a>
              <?php endif; ?>
            </div>
          <?php endforeach; ?>
        </div>
        <div class="xemthem"><a href="#">Xem thêm</a></div>
      </div>
    </div>
  </div>
</div>
</body>
</html>
