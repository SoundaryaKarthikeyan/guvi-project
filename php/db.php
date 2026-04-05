<?php
// 1. Load Composer
require_once __DIR__ . '/../vendor/autoload.php';

// --- MONGODB CONNECTION ---
$collection = null;
try {
    // Check if the MongoDB class actually exists before trying to use it
    if (!class_exists('MongoDB\Client')) {
        error_log("CRITICAL: MongoDB Extension is NOT loaded in PHP.");
    } else {
        $mongoUri = getenv('MONGO_URL'); 
        $mongoClient = new MongoDB\Client($mongoUri);
        $mongoDb = $mongoClient->selectDatabase(getenv('MYSQLDATABASE'));
        $collection = $mongoDb->profiles; 
    }
} catch (Exception $e) {
    error_log("MongoDB Connection Error: " . $e->getMessage());
}

// --- REDIS CONNECTION ---
$redis = null;
try {
    if (class_exists('Predis\Client')) {
        $redis = new Predis\Client([
            'scheme'   => 'tcp',
            'host'     => getenv('REDISHOST'),
            'port'     => getenv('REDISPORT'),
            'password' => getenv('REDISPASSWORD'),
        ]);
    }
} catch (Exception $e) {
    error_log("Redis Connection Error: " . $e->getMessage());
}

// --- MYSQL CONNECTION ---
$conn = new mysqli(getenv('MYSQLHOST'), getenv('MYSQLUSER'), getenv('MYSQLPASSWORD'), getenv('MYSQLDATABASE'), getenv('MYSQLPORT'));
?>
