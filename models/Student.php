<?php
require_once __DIR__ . '/../core/connect.php';

class Student{
    private $conn;
    public function __construct(){
        $this->conn = Database::getsql()->getConnection();
    }
    public function getAll() {
        $data = $this->conn->prepare("SELECT * FROM students ORDER BY id DESC");
        $data->execute();
        return $data->fetchAll(PDO::FETCH_ASSOC); // Trả về một mảng
    }
    public function countStudents(){
        $data = $this->conn->prepare("SELECT COUNT(*) AS total FROM students");
        $data->execute();
        $result = $data->fetch(PDO::FETCH_ASSOC);
        return $result['total'];
    }

public function add($name, $email,$dob){
    $data = $this->conn->prepare("INSERT INTO students (name, email, phone, address) VALUES (?,?,?)");
    $data->execute([$name,$email,$dob]);
}


public function delete($id){
    $data = $this->conn->prepare("DELETE FROM students WHERE id =?");
    $data->execute([$id]);
}



}
?>