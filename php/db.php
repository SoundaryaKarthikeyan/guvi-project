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
try {
    // Railway provides the full string including credentials in MONGODB_URL
    $mongoUri = getenv('MONGODB_URL');
    
    if (!$mongoUri) {
        error_log("MongoDB URL is missing!");
    } else {
        $mongoClient = new MongoDB\Client($mongoUri);
        // Use the MySQL database name for the Mongo DB name to keep it consistent
        $mongoDb = $mongoClient->selectDatabase(getenv('MYSQLDATABASE'));
        $profilesCollection = $mongoDb->profiles;
    }
} catch (Exception $e) {
    error_log("MongoDB Connection Error: " . $e->getMessage());
}
?>
