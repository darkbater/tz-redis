<?php
include 'vendor/autoload.php';

// Включает TTL, для удаления флаша в случае сбоя
$enabled_ttl = true;

// Имя флага, для защиты серипта от повторного запуска
$flag_name = 'lock_script';

// Альтернативная библиотека
// $client = new Redis();
// $client->connect('127.0.0.1', 6379);

$client = new Predis\Client('tcp://127.0.0.1:6379');

if($client->get($flag_name)) die("Already runned..\n");
$client->set($flag_name, '1');
echo("locked\n");
if($enabled_ttl) {
    echo("set TTL\n");
    $client->expire($flag_name, 6); 
    }

echo("process");
for($i=0;$i<5;$i++){
    sleep(1);
    echo('.');
    }
echo("\n");
$client->del($flag_name);
if(!$client->get($flag_name)) echo("unlocked\n");

