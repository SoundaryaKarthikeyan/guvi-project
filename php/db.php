<?php
// 1. Load Composer Dependencies (Required for MongoDB and Redis)
require_once __DIR__ . '/../vendor/autoload.php';

// --- MYSQL CONNECTION ---
$host = getenv('MYSQLHOST');
$user = getenv('MYSQLUSER');
$pass = getenv('MYSQLPASSWORD');
$dbName = getenv('MYSQLDATABASE');
$port = getenv('MYSQLPORT');

$conn = new mysqli($host, $user, $pass, $dbName, $port);

if ($conn->connect_error) {
    error_log("MySQL Connection Failed: " . $conn->connect_error);
}

// --- REDIS CONNECTION (For Sessions) ---
try {
    $redis = new Predis\Client([
        'scheme'   => 'tcp',
        'host'     => getenv('REDISHOST'),
        'port'     => getenv('REDISPORT'),
        'password' => getenv('REDISPASSWORD'),
    ]);
    // Test connection
    $redis->connect();
} catch (Exception $e) {
    error_log("Redis Connection Failed: " . $e->getMessage());
}

// --- MONGODB CONNECTION ---
// --- MONGODB CONNECTION ---
try {
    // Use the exact name from your Railway Variables tab
    $mongoUri = getenv('MONGO_URL'); 
    
    $mongoClient = new MongoDB\Client($mongoUri);
    $mongoDb = $mongoClient->selectDatabase(getenv('MYSQLDATABASE'));
    
    // We name it $collection here so it matches your profile.php
    $collection = $mongoDb->profiles; 
} catch (Exception $e) {
    error_log("MongoDB Error: " . $e->getMessage());
}
?>
