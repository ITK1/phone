<?php
require_once __DIR__ . '/../config/db.php';


class course {
private $pdo;


public function __construct() {
$db = new Database();
$this->pdo = $db->connect();
}


public function getAll() {
$stmt = $this->pdo->query("SELECT * FROM courses ORDER BY id DESC");
return $stmt->fetchAll();
}
public function countCourses() {
    $stmt = $this->pdo->query("SELECT COUNT(*) FROM courses");
    return $stmt->fetchColumn();
}


public function getById($id) {
$stmt = $this->pdo->prepare("SELECT * FROM courses WHERE id = ?");
$stmt->execute([$id]);
return $stmt->fetch();
}


public function create($name, $description) {
$stmt = $this->pdo->prepare("INSERT INTO courses (name, description) VALUES (?, ?)");
return $stmt->execute([$name, $description]);
}


public function update($id, $name, $description) {
$stmt = $this->pdo->prepare("UPDATE courses SET name = ?, description = ? WHERE id = ?");
return $stmt->execute([$name, $description, $id]);
}


public function delete($id) {
$stmt = $this->pdo->prepare("DELETE FROM courses WHERE id = ?");
return $stmt->execute([$id]);
}
}