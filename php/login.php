<?php
// Include the central connection hub
include "db.php";

// Check if POST data exists
if (isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // 1. Prepare MySQL Statement (Security Requirement)
    $stmt = $conn->prepare("SELECT password FROM users WHERE email=?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($row = $res->fetch_assoc()) {
        // 2. Verify Hashed Password
        if (password_verify($password, $row['password'])) {
            
            // 3. Update Redis Session (The Fix)
            // We MUST use the "session:" prefix to match the profile.php check
            $sessionKey = "session:" . $email;
            $redis->set($sessionKey, "active");
            
            // Set session to expire in 1 hour (3600 seconds)
            $redis->expire($sessionKey, 3600); 

            echo "success";
        } else {
            echo "fail";
        }
    } else {
        echo "fail";
    }
} else {
    echo "fail";
}
?>
