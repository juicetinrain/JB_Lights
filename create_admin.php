<?php
// create_admin.php - One-time script to create admin account
require_once 'db/db_connect.php';

$admin_email = "admin@jblights.com";
$admin_password = "admin"; // Change this to a secure password
$admin_name = "System Administrator";

// Check if admin already exists
$stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
$stmt->bind_param("s", $admin_email);
$stmt->execute();
$check = $stmt->get_result();

if ($check->num_rows === 0) {
    // Insert admin user
    $user_type = 'admin';
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, user_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $admin_name, $admin_email, $admin_password, $user_type);
    
    if ($stmt->execute()) {
        echo "Admin account created successfully!<br>";
        echo "Email: " . $admin_email . "<br>";
        echo "Password: " . $admin_password . "<br>";
        echo "<strong>IMPORTANT: Delete this file after creating the admin account!</strong>";
    } else {
        echo "Error creating admin account: " . $conn->error;
    }
} else {
    echo "Admin account already exists!";
}

$stmt->close();
$conn->close();
?>