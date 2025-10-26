<?php
require_once __DIR__ . '/../models/schedule';

class ScheduleController{
    private $scheduleController;

    public function index(){
        return $this->scheduleController->getAll();
    }

    public function add($student_id, $courses_id, $dob){
        return $this->scheduleController->add($student_id,$courses_id,$dob);
    }
}

?>