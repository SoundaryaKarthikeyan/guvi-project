<?php
include "db.php";

$email = $_REQUEST['email'] ?? "";

// Check session
if (!$redis->get($email)) {
    echo json_encode(["status" => "unauthorized"]);
    exit;
}

// ======================
// FETCH PROFILE (GET)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    $data = $collection->findOne(["email" => $email]);

    if ($data) {
        echo json_encode([
            "status" => "success",
            "name" => $data['name'] ?? "",
            "age" => $data['age'] ?? "",
            "dob" => $data['dob'] ?? "",
            "contact" => $data['contact'] ?? "",
            "photo" => $data['photo'] ?? ""
        ]);
    } else {
        echo json_encode(["status" => "empty"]);
    }

    exit;
}

// ======================
// SAVE PROFILE (POST)
// ======================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = $_POST['name'] ?? "";

    $photoPath = null;

    // Handle file upload
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {

        $filename = time() . "_" . basename($_FILES['photo']['name']);

        $target = "../assets/uploads/" . $filename;

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target)) {
            $photoPath = "assets/uploads/" . $filename;
        }
    }

    // Build data
    $data = [
        "email" => $email,
        "name" => $name,
        "age" => $_POST['age'],
        "dob" => $_POST['dob'],
        "contact" => $_POST['contact']
    ];

    // Only update photo if uploaded
    if ($photoPath) {
        $data["photo"] = $photoPath;
    }

    // Update MongoDB
    $collection->updateOne(
        ["email" => $email],
        ['$set' => $data],
        ["upsert" => true]
    );

    echo json_encode(["status" => "saved"]);
}
?>