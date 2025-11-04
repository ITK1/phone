<?php
require_once __DIR__ . '/../core/connect.php';

class Course {
    private $conn;

    public function __construct() {
        $this->conn = Database::getsql()->getConnection();
    }

    public function getCourseById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM courses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAllCourses() {
        $stmt = $this->conn->prepare("SELECT * FROM courses ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addCourses($name, $teacher, $time, $description, $price) {
        $stmt = $this->conn->prepare("
            INSERT INTO courses (name, teacher, time, description, price)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$name, $teacher, $time, $description, $price]);
        return $this->conn->lastInsertId();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM courses WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    // Thêm video
    public function addVideo($course_id, $title, $video_url, $description, $is_demo, $duration) {
        $sql = "INSERT INTO course_videos (course_id, title, video_url, description, is_demo, duration)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$course_id, $title, $video_url, $description, $is_demo, $duration]);
    }
    
    

// Lấy video theo khóa học
public function getVideoCourses($course_id) {
    $stmt = $this->conn->prepare("SELECT * FROM course_videos WHERE course_id = ?");
    $stmt->execute([$course_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Lấy video demo
public function getDemoVideo($course_id) {
    $sql = "SELECT * FROM course_videos WHERE course_id = ? AND is_demo = 1 LIMIT 1";
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([$course_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


    public function getTotalDuration($course_id){
        $data = $this->conn->prepare("SELECT SUM(duration) AS total_duration FROM course_videos WHERE course_id = ?");
        $data->execute([$course_id]);
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result ? (int)$result['total_duration'] :0; 
    }
    
}
?>
