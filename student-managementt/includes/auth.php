<?php
session_start();
require_once 'db.php';

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']) && isset($_SESSION['username']);
}

// Check if user is admin
function isAdmin() {
    return isLoggedIn() && $_SESSION['role'] === 'admin';
}

// Check if user is student
function isStudent() {
    return isLoggedIn() && $_SESSION['role'] === 'student';
}

// Login function
function login($username, $password) {
    $sql = "SELECT u.*, s.full_name, s.student_code 
            FROM users u 
            LEFT JOIN students s ON u.student_id = s.id 
            WHERE u.username = ?";
    $user = fetchSingle($sql, [$username]);
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['student_id'] = $user['student_id'];
        $_SESSION['full_name'] = $user['full_name'];
        $_SESSION['student_code'] = $user['student_code'];
        
        return true;
    }
    return false;
}

// Logout function
function logout() {
    session_destroy();
    header('Location: ../index.php');
    exit();
}

// Require login
function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: ../index.php');
        exit();
    }
}

// Require admin
function requireAdmin() {
    requireLogin();
    if (!isAdmin()) {
        header('Location: ../dashboard.php');
        exit();
    }
}

// Require student
function requireStudent() {
    requireLogin();
    if (!isStudent()) {
        header('Location: ../dashboard.php');
        exit();
    }
}

// Get current user info
function getCurrentUser() {
    if (isLoggedIn()) {
        return [
            'id' => $_SESSION['user_id'],
            'username' => $_SESSION['username'],
            'role' => $_SESSION['role'],
            'student_id' => $_SESSION['student_id'],
            'full_name' => $_SESSION['full_name'],
            'student_code' => $_SESSION['student_code']
        ];
    }
    return null;
}

// Hash password
function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

// Validate password
function validatePassword($password) {
    return strlen($password) >= 6;
}

// Check if username exists
function usernameExists($username) {
    $sql = "SELECT id FROM users WHERE username = ?";
    return fetchSingle($sql, [$username]) !== false;
}

// Check if email exists
function emailExists($email) {
    $sql = "SELECT id FROM students WHERE email = ?";
    return fetchSingle($sql, [$email]) !== false;
}

// Check if student code exists
function studentCodeExists($studentCode) {
    $sql = "SELECT id FROM students WHERE student_code = ?";
    return fetchSingle($sql, [$studentCode]) !== false;
}
?>
