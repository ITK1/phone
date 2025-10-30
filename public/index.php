<?php
require_once '../Models/Course.php';
$courseModel = new Course();
$courses = $courseModel->getAllCourses();
?>
<!DOCTYPE html>
<html lang="vn">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Khóa Học</title>
    <link rel="stylesheet" href="../assets/style.css" />
  </head>
  <body>
    <div id="main">
      <!-- Header -->
      <div id="header">
        <div class="nav-login">
          <div id="img-header"></div>
          <ul>
            <li id="hotline">Hotline: 0879.888.186 - 0879.888.986</li>
            <li><a href="#">Kích hoạt</a></li>
            <li><a href="#">Đăng ký</a></li>
            <li><a href="#">Đăng Nhập</a></li>
          </ul>
        </div>

        <div class="nav">
          <ul>
            <li><a href="#">Trang chủ</a></li>
            <li class="shownav">
              <a href="#">Khóa học</a>
              <ul class="navcon">
                <li><a href="#">Toeic</a></li>
                <li><a href="#">IELTS</a></li>
                <li><a href="#">Anh văn</a></li>
              </ul>
            </li>
            <li><a href="#">Thời khóa biểu</a></li>
            <li><a href="#">Lịch dạy</a></li>
            <li><a href="#">Thông tin</a></li>
            <li><a href="./students.php">Quản lý sinh viên</a></li>
          </ul>
        </div>
      </div>

      <!-- Slider -->
      <div id="slider"></div>

      <!-- Giới thiệu -->
      <div id="content">
        <div class="content-siler">
          <div class="text-paner">
            <h1>Khóa Học Cấp Tốc</h1>
          </div>
          <div class="content-item">
            <div><b>Học tập online</b></div>
            <div>Đa dạng các chủ đề khác nhau</div>
          </div>

          <div class="content-item">
            <div><b>Giảng viên chuyên nghiệp</b></div>
            <div>Kinh nghiệm thực tế và chuyên môn cao</div>
          </div>

          <div class="content-item">
            <div><b>Khóa học trọn đời</b></div>
            <div>Học mọi lúc mọi nơi</div>
          </div>
        </div>
      </div>

      <!-- Khóa học -->
      <div id="course">
        <div class="header-text">Các khóa học lập trình cơ bản</div>
        <div class="header-textnho">
          Danh mục khóa học được nhiều người tham gia học tập nhất
        </div>

        <div class="course-content">
          <?php if (!empty($courses)): ?>
            <?php foreach ($courses as $course): ?>
              <div class="course-box">
                <div class="img">
                  <img
                    src="../assets/img/kh1.png"
                    alt="<?= htmlspecialchars($course['name']) ?>"
                  />
                </div>
                <div class="course-text"><?= htmlspecialchars($course['name']) ?></div>
                <div class="date">
                  <div>24 Bài giảng</div>
                  <div><?=$course['time']?></div>
                </div>
                <div class="teacher">
                  <div class="name">
                    <span>Giảng viên: </span><?= htmlspecialchars($course['teacher']) ?>
                  </div>
                  <div class="price"><?= number_format($course['price'], 0, ',', '.') ?>đ</div>
                </div>
                <div class="button">
                  <div class="button-dk">
                    <a href="course_detail.php?id=<?= $course['id'] ?>">Đăng ký</a>
                  </div>
                  <div class="button-them">
                    <a href="#">Thêm vào giỏ</a>
                  </div>
                </div>
              </div>
            <?php endforeach; ?>
          <?php else: ?>
            <p>Chưa có khóa học nào!</p>
          <?php endif; ?>
        </div>

        <div class="xemthem">
          <button class="xemthem">Xem Thêm</button>
        </div>
      </div>

      <div id="footer"></div>
    </div>
  </body>
</html>
