<?php

require_once '../models/Student.php';

class StudentController{
    private $studentModel = null;
    
    public function __construct() {
        $this->studentModel = new Student();
    }

    public function index() {
        return $this->studentModel->countStudents();
    }

    public function store($name, $email, $dob) {
        return $this->studentModel->add($name, $email,$dob);
    }



}

?>