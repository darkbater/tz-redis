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

$client->set('lock_script', '1');
$client->expire('lock_script', 5);  // TTL задается отдельно

echo('lock_script contain:');
echo($client->get('lock_script'));

sleep(3);
$newvalue=$client->get('lock_script');

if(!$newvalue) echo('lock_script empty');
else echo("lock_script contain {$newvalue}");
sleep(3);
$newvalue=$client->get('lock_script');

if(!$newvalue) echo('lock_script empty');
else echo("lock_script contain {$newvalue}");



// $client->get

if($client) echo('ok');









