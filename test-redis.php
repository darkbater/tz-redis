<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
include 'vendor/autoload.php';



$redis = new Redis();
$redis->connect('127.0.0.1', 6379);







echo('ok1');


// phpinfo();

$client = new Predis\Client('tcp://127.0.0.1:6379');


// $client->get

if($client) echo('ok');









