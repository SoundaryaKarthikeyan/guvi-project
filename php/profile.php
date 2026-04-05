<?php
header('Content-Type: application/json');
include "db.php";

$email = $_REQUEST['email'] ?? "";

if (empty($email)) {
    echo json_encode(["status" => "error", "message" => "No email provided"]);
    exit;
}

// 3. SESSION CHECK (Using Redis)
try {
    if (!$redis || !$redis->exists("session:" . $email)) {
        echo json_encode(["status" => "unauthorized"]);
        exit;
    }
} catch (Exception $e) {
    // If Redis fails, we don't necessarily want to lock the user out, but we log it
    error_log("Session check bypassed due to Redis error");
}

// ======================
// FETCH PROFILE (GET)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (!$collection) {
        echo json_encode(["status" => "error", "message" => "MongoDB Connection Missing"]);
        exit;
    }

    try {
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
        echo json_encode(["status" => "error", "message" => "Read Error: " . $e->getMessage()]);
    }
    exit;
}

// ======================
// SAVE PROFILE (POST)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!$collection) {
        echo json_encode(["status" => "error", "message" => "MongoDB Connection Missing"]);
        exit;
    }

    $photoPath = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
        $filename = time() . "_" . basename($_FILES['photo']['name']);
        $target = "../assets/uploads/" . $filename;
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photoPath = "assets/uploads/" . $filename;
        }
    }

    $updateData = [
        "name"    => $_POST['name'] ?? "",
        "age"     => $_POST['age'] ?? "",
        "dob"     => $_POST['dob'] ?? "",
        "contact" => $_POST['contact'] ?? ""
    ];

    if ($photoPath) { $updateData["photo"] = $photoPath; }

    try {
        $collection->updateOne(
            ["email" => $email],
            ['$set' => $updateData],
            ["upsert" => true]
        );
        echo json_encode(["status" => "saved"]);
    } catch (Exception $e) {
        echo json_encode(["status" => "error", "message" => "Write Error: " . $e->getMessage()]);
    }
    exit;
}
?>
