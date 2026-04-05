<?php
include "db.php";

// Allow CORS if needed, and set JSON header
header('Content-Type: application/json');

$email = $_REQUEST['email'] ?? "";

// Safety check: Ensure $redis exists before calling get()
if (!$redis || !$redis->get("session:" . $email)) {
    echo json_encode(["status" => "unauthorized"]);
    exit;
}
// ... rest of your code
