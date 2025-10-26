<?php
require_once  '../core/connect.php';

class Student{
    private $conn;
    public function __construct(){
        $this->conn = Database::getsql()->getConnection();
    }

public function getAllStudents(){
    $data= $this->conn->prepare("SELECT * FROM students ORDER BY id DESC LIMIT 5");
    $data->execute();
    return $data->fetchAll(PDO::FETCH_ASSOC);
}


public function add($name, $email,$dob){
    $data = $this->conn->prepare("INSERT INTO students (name, email, phone, address) VALUES (?,?,?,?)");
    $data->execute([$name,$email,$dob]);
}


public function delete($id){
    $data = $this->conn->prepare("DELETE FROM students WHERE id =?");
    $data->execute([$id]);
}



}
?>