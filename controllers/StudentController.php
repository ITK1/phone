<?php
require_once __DIR__ . '/../models/Student.php';

class StudentController{
    private $studentModel = null;
    
    public function __construct() {
        $this->studentModel = new Student();
    }

    public function index() {
        return $this->studentModel->getAllStudents();
    }

    public function store($name, $email, $dob) {
        return $this->studentModel->add($name, $email,$dob);
    }



}

?>