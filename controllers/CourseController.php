<?php
require_once __DIR__ . '/../models/Course.php';

class CourseController {
    private $courseModel;

    public function __construct() {
        $this->courseModel = new Course();
    }

    // Lấy tất cả khóa học
    public function index() {
        return $this->courseModel->getAllCourses();
    }

    // Lấy chi tiết 1 khóa học
    public function show($id) {
        return $this->courseModel->getCourseById($id);
    }

    // Thêm khóa học
    public function store($name, $teacher, $time, $description, $price) {
        return $this->courseModel->addCourses($name, $teacher, $time, $description, $price);
    }

    // Xóa khóa học
    public function destroy($id) {
        return $this->courseModel->delete($id);
    }
}
?>
