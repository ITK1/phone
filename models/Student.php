<?php
require_once __DIR__ . '/../config/db.php';

class Student{
    private $conn;
    private $table ="student";

    public function __construct(){ // mo file va data
        $db = new Data();
        $this->conn = $db->connect();
    }

    public function getAll(){
        $stmt = $this->conn->prepare("SELECT * FROM $this -> table ORDER BY id DESC");
        $stmt->execute();
        return $stmt;
    }

    public function getById($id){
$stmt = $this->conn->prepare(" SELECT * FROM $this-> table WHERE id=?");
$stmt->execute([$id]);
return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function add($name,$email,$dob){
        $stmt = $this->conn->prepare("INSERT INTO $this ->table (name, email,dob) VALUES (?,?,?)");
        return $stmt->execute([$name,$email,$dob]);
    }

    public function update($id,$name, $email,$dob){
        $stmt = $this->conn->prepare("UPDATE $this->table SET name=?, email?, dob=?");
        return $stmt->execute([$name,$email,$dob,$id]);
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM $this->table WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?> 