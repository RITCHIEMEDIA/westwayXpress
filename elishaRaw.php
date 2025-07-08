<?php
require_once 'config/database.php';

// This script should only be run once to create the admin user
// Delete this file after running it for security

$database = new Database();
$db = $database->getConnection();

// Admin user details - CHANGE THESE BEFORE RUNNING
$admin_username = 'AdminErimus';
$admin_email = 'admin2@gmail.com';
$admin_password = 'Admin1231'; // Change this to a strong password
$admin_full_name = 'System Administrator';
$admin_phone = '+1234567890';

// Hash the password
$hashed_password = password_hash($admin_password, PASSWORD_DEFAULT);

try {
    // Check if admin already exists
    $check_query = "SELECT id FROM users WHERE role = 'admin' LIMIT 2";
    $check_stmt = $db->prepare($check_query);
    $check_stmt->execute();
    
    if ($check_stmt->rowCount() > 1) {
        echo "Admin user already exists!";
        exit();
    }
    
    // Insert admin user
    $query = "INSERT INTO users (username, email, password, role, full_name, phone) VALUES (?, ?, ?, 'admin', ?, ?)";
    $stmt = $db->prepare($query);
    
    if ($stmt->execute([$admin_username, $admin_email, $hashed_password, $admin_full_name, $admin_phone])) {
        echo "Admin user created successfully!<br>";
        echo "Username: $admin_username<br>";
        echo "Email: $admin_email<br>";
        echo "Password: $admin_password<br><br>";
        echo "<strong>IMPORTANT: Delete this file (setup-admin.php) immediately for security!</strong>";
    } else {
        echo "Error creating admin user.";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
