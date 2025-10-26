<?php
require_once  '../core/connect.php';

class Course{
    private $conn;
    
    public function __construct(){
        $this->conn = Database::getsql()->getConnection();
    }

    public function getAll(){
        $data = $this->conn->prepare("SELECT * FROM courses ORDER BY id DESC LIMIT 5");
        return $data->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name,$teacher){
        $data = $this->conn->prepare("INSERT INTO course (name, teacher) VALUES (?,?)");
        return $data->execute([$name,$teacher]);
    }

    public function delete($id){
        $data= $this->conn->prepare("DELETE FROM courses WHERE id = ?");
        return $data->execute([$id]);
    }

}

?>