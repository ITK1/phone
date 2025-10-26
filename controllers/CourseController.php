<?php
require_once __DIR__ .'/../models/Course.php';

class CourseController{
    private $courseModel;


    public function __construct() {
        $this->courseModel = new Course();
    }

    public function index() {
        return $this->courseModel->getAll();
    }

    public function store($name,$teacher){
        return $this->courseModel->add($name,$teacher);
    }

    public function destroy($id){
        return $this->courseModel->delete($id);
    }


}


?>