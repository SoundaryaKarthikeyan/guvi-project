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

// --- MONGODB CONNECTION (For Profiles) ---
try {
    // Railway usually provides a full MONGODB_URL
    $mongoClient = new MongoDB\Client(getenv('MONGODB_URL'));
    // Select the database (using your MySQL DB name as a default)
    $mongoDb = $mongoClient->selectDatabase($dbName);
    $profilesCollection = $mongoDb->profiles;
} catch (Exception $e) {
    error_log("MongoDB Connection Failed: " . $e->getMessage());
}
?>
