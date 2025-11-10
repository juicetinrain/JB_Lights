<?php
// db_connect.php
session_start();

$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'jb_lights';

$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) {
    die("Database connection failed: " . $conn->connect_error);
}

// Check if user is logged in
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

// Get current user data
function getCurrentUser() {
    global $conn;
    if (isLoggedIn()) {
        $user_id = $_SESSION['user_id'];
        $result = $conn->query("SELECT * FROM users WHERE id = $user_id");
        return $result->fetch_assoc();
    }
    return null;
}

// Check if user is admin
function isAdmin() {
    if (isLoggedIn()) {
        $user = getCurrentUser();
        return $user && $user['user_type'] === 'admin';
    }
    return false;
}
?>