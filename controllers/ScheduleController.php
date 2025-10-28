<?php
require_once  '/models/schedule';

class ScheduleController{
    private $scheduleController;

    public function index(){
        return $this->scheduleController->countSchedules();
    }

    public function add($student_id, $courses_id, $dob){
        return $this->scheduleController->add($student_id,$courses_id,$dob);
    }
}

?>