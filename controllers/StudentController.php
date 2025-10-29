<?php

require_once '../models/Student.php';

class StudentController{
    private $studentModel = null;
    
    public function __construct() {
        $this->studentModel = new Student();
    }

    public function index() {
    return $this->studentModel->getAll(); // Lấy danh sách sinh viên, không phải số lượng
}


    public function store($name, $email, $phone) {
        return $this->studentModel->add($name, $email,$phone);
    }



}

?>