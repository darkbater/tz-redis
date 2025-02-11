<?php
error_reporting(E_ALL);
ini_set('display_errors','1');
include 'vendor/autoload.php';

// $redis = new Redis();
// $redis->connect('127.0.0.1', 6379);

# Work - * @method mixed  set($key, $value, $EX, $exSecs = null, $PX, $psMsecs = null, $flag = null)

$client = new Predis\Client('tcp://127.0.0.1:6379');
$flag = 'lock_script';

if($client->get($flag)) die('Already runned..');

$client->set($flag, '1');
$client->expire($flag, 6);  // TTL задается отдельно

$client->del($flag);

echo("{$flag} contain:");
echo($client->get($flag));

echo("{$flag} contain:");
echo($client->get($flag));

sleep(5);
// $newvalue=$client->get($flag);

// if(!$newvalue) echo($flag. ' empty');
// else echo("$flag contain {$newvalue}");

// sleep(3);
// $newvalue=$client->get($flag);

// if(!$newvalue) echo($flag. 'empty');
// else echo("{$flag} contain {$newvalue}");



// $client->get

if($client) echo('ok');









