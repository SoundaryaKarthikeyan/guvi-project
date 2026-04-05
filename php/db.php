<?php
require_once __DIR__ . '/../vendor/autoload.php';


$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$db   = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');


$conn = new mysqli($host, $user, $pass, $db, $port);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// --- MongoDB ---
$mongoUri = getenv('MONGO_URL') ?: "mongodb://localhost:27017";
$mongo = new MongoDB\Client($mongoUri);
$collection = $mongo->selectDatabase('guvi')->profiles;

// --- Redis (Predis) ---
$redisUrl = getenv('REDIS_URL') ?: "tcp://127.0.0.1:6379";
$redis = new Predis\Client($redisUrl);
?>
