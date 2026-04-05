<?php
// 1. Load Composer Dependencies
require_once __DIR__ . '/../vendor/autoload.php';

// --- MYSQL CONNECTION ---
$conn = new mysqli(getenv('MYSQLHOST'), getenv('MYSQLUSER'), getenv('MYSQLPASSWORD'), getenv('MYSQLDATABASE'), getenv('MYSQLPORT'));

// --- REDIS CONNECTION ---
$redis = null; 
try {
    $redis = new Predis\Client([
        'scheme'   => 'tcp',
        'host'     => getenv('REDISHOST'),
        'port'     => getenv('REDISPORT'),
        'password' => getenv('REDISPASSWORD'),
    ]);
    $redis->connect();
} catch (Exception $e) {
    error_log("Redis Connection Failed: " . $e->getMessage());
}

// --- MONGODB CONNECTION ---
$collection = null;
try {
    $mongoUri = getenv('MONGO_URL'); 
    if ($mongoUri) {
        $mongoClient = new MongoDB\Client($mongoUri);
        // Use MYSQLDATABASE name as the Mongo DB name for consistency
        $mongoDb = $mongoClient->selectDatabase(getenv('MYSQLDATABASE'));
        $collection = $mongoDb->profiles; 
    }
} catch (Exception $e) {
    error_log("MongoDB Error: " . $e->getMessage());
}
?>
