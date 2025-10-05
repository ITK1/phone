<?php
// Database configuration
$host = 'localhost';
$dbname = 'student_management';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Kết nối CSDL thất bại: " . $e->getMessage());
}

// Helper function to execute queries
function executeQuery($sql, $params = []) {
    global $pdo;
    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    } catch(PDOException $e) {
        die("Lỗi truy vấn: " . $e->getMessage());
    }
}

// Helper function to fetch single row
function fetchSingle($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

// Helper function to fetch all rows
function fetchAll($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

// Helper function to get last inserted ID
function getLastInsertId() {
    global $pdo;
    return $pdo->lastInsertId();
}
?>
