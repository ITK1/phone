<?php
require_once __DIR__ . '/../config/db.php';

class Student{
    private $pdo;

    public function __construct(){

        $db = new Database();
        $this->pdo = $db->connect();
    }

    public function getAll(){
        $stmt =$this->pdo->query("SELECT * FROM students ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    public function getById($id){
        $stmt =$this ->pdo->prepare("SELECT * FROM stundents WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($name, $email,$phone){
        $stmt = $this->pdo->prepare("INSERT INTO students (name, email, phone) VALUES (?,?,?)");
        return $stmt->execute([$name,$email,$phone]);
    }
    public function update($id, $name, $email, $phone) {
        $stmt = $this->pdo->prepare(" UPDATE students SET name = ?, email = ?, phone = ? WHERE id = ?");
        return $stmt->execute([$name,$email,$phone,$id]);
    }
    public function delete($id){
        $stmt=$this->pdo->prepare("DELETE FROM student WHERE id = ?");
        return $stmt->execute([$id]);
    }
    public function getCourses($student_id){
        $stmt=$this->pdo->prepare("SELECT c.* FROM courses c JOIN enrollments e ON c.id = e.course_id WHERE e.student_id = ?");
    $stmt->executefetchAll();
    return $stmt->fetchAll();
}
}

?>