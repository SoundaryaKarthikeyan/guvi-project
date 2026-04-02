<?php
require '../vendor/autoload.php';

$redis = new Predis\Client();

$redis->set('test', 'working');
echo $redis->get('test');
?>