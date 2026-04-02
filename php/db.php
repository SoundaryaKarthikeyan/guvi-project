<?php

// MySQL
$conn = new mysqli("localhost", "root", "", "guvi");

if ($conn->connect_error) {
    die("MySQL Connection Failed");
}

// Load Composer
require '../vendor/autoload.php';

// MongoDB
$mongo = new MongoDB\Client("mongodb://localhost:27017");
$collection = $mongo->guvi->profiles;

// Redis (Predis)
$redis = new Predis\Client([
    'scheme' => 'tcp',
    'host'   => '127.0.0.1',
    'port'   => 6379,
]);

?>