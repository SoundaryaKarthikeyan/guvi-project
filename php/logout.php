<?php
// 1. Enable error reporting for one last check
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Include your central connection hub
include "db.php";

// Set JSON header
header('Content-Type: application/json');

// Get email from the request
$email = $_POST['email'] ?? "";

if (!empty($email)) {
    try {
        // 3. Remove the session from Redis
        // Ensure the prefix "session:" matches your login.php logic
        if ($redis) {
            $redis->del("session:" . $email);
        }
        
        echo json_encode(["status" => "success"]);
    } catch (Exception $e) {
        // Log the error but don't crash with a 500
        error_log("Logout Redis Error: " . $e->getMessage());
        echo json_encode(["status" => "error", "message" => "Server error during logout"]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "No email provided"]);
}
exit;
?>
