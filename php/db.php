<?php
require_once __DIR__ . '/../vendor/autoload.php';

// Fetching variables from Railway environment
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

// CRITICAL: Check if variables are actually present
if (!$host || !$user) {
    die("Error: MySQL Environment Variables are not set in Railway.");
}

// Line 12 - This now uses the Cloud network settings
$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
