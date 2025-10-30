<?php
require_once __DIR__ . '/../core/connect.php';

 class Course {
private $conn;

public function __construct(){
    $this->conn = Database::getsql()->getConnection();
}

public function getCourseById($id){
    $data = $this->conn->prepare("SELECT * FROM courses WHERE id = ?");
    $data->execute([$id]);
    return $data->fetch(PDO::FETCH_ASSOC);
}

public function getAllCourses(){
    $data = $this->conn->prepare("SELECT * FROM courses ORDER BY id DESC");
    $data->execute();
    return $data->fetchAll(PDO::FETCH_ASSOC);
}


public function addCourses($name, $teacher,$time, $description, $price ){
    $data = $this->conn->prepare("INSERT INTO courses (name, teacher, time, `description`, price) VALUES (?,?,?,?,?)");
    $data ->execute([$name,$teacher,$time,$description,$price]);
}

 }

?>