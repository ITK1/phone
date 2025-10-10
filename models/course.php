<?php
require_once __DIR__ . '/../config/db.php';

class Course {
    private $conn;
    private $table = "courese";

    public function __construct(){
        $db =new Data();
        $this->conn = $db->connect();
    }

    public function getAll(){
        $stmt = $this->conn->prepare("SELECT * FROM $this-> table ORDER BY id DESC ");
        $stmt->execute();
        return $stmt;
    }

    public function add($title, $description){
        $stmt =$this->conn->prepare("INSERT INTO $this->table (title, description) VALUES (?,?)");
        return $stmt->execute([$title, $description]);
    }

    public function getById($id){
        $stmt = $this->conn->prepare("SELECT * FROM $this-> table WHERE id=?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id,$title,$description){
        $stmt = $this->conn->prepare("UPDATE $this->table SET title=?, description=? WHERE id=?");
        $stmt->execute([$id,$title,$description]);
    }

    public function delete($id){
        $stmt = $this->conn->prepare("DELETE FROM $this-> table WHERE id=?");
        return $stmt->execute([$id]);
    }
}
?>