<?php
 require_once __DIR__ . '/../models/Course.php';

 $courseModel = new Course();
 $courses = $courseModel->getAllCourses();
 


//  $_POST là mảng chứa toàn bộ dữ liệu form gửi lên.

// ?? '' là toán tử “null coalescing” — nếu không có giá trị thì trả về mặc định (ở đây là chuỗi rỗng).

// isset($_POST['is_demo']) ? 1 : 0
// 👉 Vì checkbox chỉ gửi dữ liệu khi được tick, nên ta kiểm tra:

// Nếu checkbox "Học thử" được chọn → gán $is_demo = 1



// $duration là thời lượng video (phút) bạn nhập trong form.

// strpos($video_url, 'youtube.com/watch') → kiểm tra xem link có chứa “youtube.com/watch” không.

// parse_url($video_url, PHP_URL_QUERY) → tách phần query của URL (phần sau dấu ?, ví dụ: v=abc123).

// parse_str() → biến chuỗi v=abc123 thành mảng ['v' => 'abc123'].

// Nếu tồn tại tham số v, tạo lại link nhúng hợp lệ:

    if($_SERVER['REQUEST_METHOD']==='POST'){
        $course_id  = $_POST['course_id'] ?? '';
        $title      = $_POST['title'] ?? '';
        $video_url  = $_POST['video_url'] ?? '';
        $description = $_POST['description'] ?? '';
        $is_demo    = isset($_POST['is_demo']) ? 1 : 0;
        $duration   = $_POST['duration'] ?? 0; // ✅ thêm dòng này


        // Nếu không chọn → $is_demo = 0

// empty() kiểm tra xem giá trị có rỗng không.

// Nếu 3 trường bắt buộc (course_id, title, video_url) đều có dữ liệu thì mới xử lý thêm vào database.

// Nếu thiếu bất kỳ cái nào → hiển thị lỗi.

        if(!empty($course_id) || !empty($title) || !empty($video_url)){
            //chuyển sang link youtube
            if (strpos($video_url, 'youtube.com/watch') !== false) {
                parse_str(parse_url($video_url, PHP_URL_QUERY), $params);
                if (isset($params['v'])) {
                    $video_url = "https://www.youtube.com/embed/" . $params['v'];
                }
            }


            // truyền thêm thời lương video vào
            $courseModel->addVideo($course_id, $title, $video_url, $description, $is_demo, $duration);
                header("location : add_video.php");
                exit;
            
            }else{
                $error =" Vui Lòng Nhập Đủ Thông Tin Video:";


        }

    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Video</title>
    <link rel="stylesheet" href="../assets/style.css">
</head>
<body>
    <div id="add-video">
        
        <div class="box">
            <div class="text"><h2>Thêm Video Cho Khóa Học</h2></div>
        <?php if(!empty($error)): ?>
        <p style="color:red;"> <?= htmlspecialchars($error)?></p>
        <?php endif; ?>

        <form action="" method="POST">
        
            <div class="input-box">
                <label>Chọn khóa học:</label><br>
                <select name="course_id" required>
                  <option value="">-- Chọn khóa học --</option>
                  <?php foreach ($courses as $course): ?>
                      <option value="<?= $course['id'] ?>">
                          <?= htmlspecialchars($course['name']) ?> (GV: <?= htmlspecialchars($course['teacher']) ?>)
                      </option>
                  <?php endforeach; ?>
              </select>
            </div>

            <div class="input-box">
                <label>Tiêu đề video:</label><br>
                <input type="text" name="title" required><br><br>
            </div>     

            <div class="input-box">
                <label>Link video YouTube:</label><br>
                 <input type="text" name="video_url" required><br><br>
            </div>       

            <div class="input-box">
                <label>Mô tả video:</label><br>
                <textarea name="description"></textarea><br><br>
            </div> 
            <div class="input-box">
                <label>Thời lượng (phút):</label><br>
                <input type="number" name="duration" min="1" placeholder="Nhập thời lượng video"><br><br>

            </div>
            
            <div class="input-box">
                <label>
                <input type="checkbox" name="is_demo" value="1"> Đây là video học thử (demo)
                </label><br><br>
            </div>

            <div class="btn">
                <button type="submit">Thêm video</button>
            </div>
        </form>
    </div>
    </div>
</body>
</html>