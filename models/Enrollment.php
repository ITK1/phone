<?php
require_once __DIR__ . '/../config/db.php';

class enrollment{
    private $conn;
    private $table = "enrollment";

    public function __construs(){
        $db = new Data();
        $this->conn = $db->connect();
    }

    public function getAll(){
        $sql ="SELECT e.id, s.name AS student_name, c.title AS course_title FROM enrollments e JOIN studens s ON e.student_id = s.id JOIN courses c ON e.course_id = c.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt;
    }

    public function add($student_id, $course_id){
        $stmt = $this->conn->prepare("INSERT INTO enrollments (student_id , course_id) VALUES (?,?)");
        return $stmt->execute([$student_id,$course_id]);
    }
}
?>