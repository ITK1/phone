<?php
require_once  'connect.php';


class Schedule{
private $conn;

public function __contruct(){
    $this->conn = Database::getsql()->getConnection();
}

public function countSchedules(){
    $sql = "SELECT s.id, st.name AS student, c.name AS courses , s.day, s.time  
    FROM schedule s 
    JOIN stundents st ON s.student_id = st.id 
    JOIN  courses C ON S.courses_id = c.id";
    $data= $this->conn->prepare($sql);
    return $data->fetchAll(PDO::FETCH_ASSOC);
}

public function add($student_id, $courses_id, $day,$time){
    $data = $this->conn->prepapre("INSERT INTO schedules ($student_id,$courses_id,$day,$time) VALUES (?,?,?,?)");
    return $data->execute([$student_id,$courses_id,$day,$time]);
}






}

?>