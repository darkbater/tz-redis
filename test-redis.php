<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
include 'vendor/autoload.php';



// $redis = new Redis();
// $redis->connect('127.0.0.1', 6379);

# Work - * @method mixed  set($key, $value, $EX, $exSecs = null, $PX, $psMsecs = null, $flag = null)





echo('ok1');


// phpinfo();

$client = new Predis\Client('tcp://127.0.0.1:6379');

// $client->set('lock_script', '1', 'EX', '5', 'PX', 5000 );
// echo($client->get('lock_script'));




// $client->get

if($client) echo('ok');









