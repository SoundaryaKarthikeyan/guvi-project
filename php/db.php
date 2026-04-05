<?php
require_once __DIR__ . '/../vendor/autoload.php';

// --- MySQL ---
// Railway gives you the full URL, or you can use individual vars
$host = getenv('MYSQLHOST') ?: 'localhost';
$user = getenv('MYSQLUSER') ?: 'root';
$pass = getenv('MYSQLPASSWORD') ?: '';
$name = getenv('MYSQLDATABASE') ?: 'guvi';
$port = getenv('MYSQLPORT') ?: '3306';

$conn = new mysqli($host, $user, $pass, $name, $port);

// --- MongoDB ---
$mongoUri = getenv('MONGO_URL') ?: "mongodb://localhost:27017";
$mongo = new MongoDB\Client($mongoUri);
$collection = $mongo->selectDatabase('guvi')->profiles;

// --- Redis (Predis) ---
$redisUrl = getenv('REDIS_URL') ?: "tcp://127.0.0.1:6379";
$redis = new Predis\Client($redisUrl);
?>
