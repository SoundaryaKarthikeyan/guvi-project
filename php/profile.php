<?php
// 1. Error reporting for debugging (Disable this once everything works)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// 2. Include the central connection file
include "db.php";

// Set JSON header for AJAX responses
header('Content-Type: application/json');

// Get the email from either GET or POST
$email = $_REQUEST['email'] ?? "";

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "No email provided"]);
    exit;
}

// 3. SESSION CHECK (Using Redis)
// Note: We use the "session:" prefix to match the login.php logic
try {
    if (!$redis || !$redis->exists("session:" . $email)) {
        echo json_encode(["status" => "unauthorized"]);
        exit;
    }
} catch (Exception $e) {
    // If Redis fails, we log it and decide if we want to block the user
    error_log("Profile Redis Check Failed: " . $e->getMessage());
}

// ======================
// FETCH PROFILE (GET)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // $collection is defined in your updated db.php
        $data = $collection->findOne(["email" => $email]);

        if ($data) {
            echo json_encode([
                "status" => "success",
                "name"    => $data['name'] ?? "",
                "age"     => $data['age'] ?? "",
                "dob"     => $data['dob'] ?? "",
                "contact" => $data['contact'] ?? "",
                "photo"   => $data['photo'] ?? ""
            ]);
        } else {
            echo json_encode(["status" => "empty"]);
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "MongoDB Read Error"]);
    }
    exit;
}

// ======================
// SAVE PROFILE (POST)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? "";
    $photoPath = null;

    // Handle File Upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $filename = time() . "_" . basename($_FILES['photo']['name']);
        $target = "../assets/uploads/" . $filename;

        // Ensure the directory exists or this will fail
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photoPath = "assets/uploads/" . $filename;
        }
    }

    // Build the Update Array
    $updateData = [
        "name"    => $name,
        "age"     => $_POST['age'] ?? "",
        "dob"     => $_POST['dob'] ?? "",
        "contact" => $_POST['contact'] ?? ""
    ];

    if ($photoPath) {
        $updateData["photo"] = $photoPath;
    }

    try {
        // Perform the Upsert (Update or Insert)
        $collection->updateOne(
            ["email" => $email],
            ['$set' => $updateData],
            ["upsert" => true]
        );
        echo json_encode(["status" => "saved"]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(["status" => "error", "message" => "MongoDB Write Error"]);
    }
    exit;
}
?>
