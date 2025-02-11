<?php
include 'vendor/autoload.php';

$error_protect = true;
// error_reporting(E_ALL);
// ini_set('display_errors','1');

// $redis = new Redis();
// $redis->connect('127.0.0.1', 6379);

# Work - * @method mixed  set($key, $value, $EX, $exSecs = null, $PX, $psMsecs = null, $flag = null)

$client = new Predis\Client('tcp://127.0.0.1:6379');
$flag = 'lock_script';

if($client->get($flag)) die("Already runned..\n");
$client->set($flag, '1');
echo("locked\n");
if($error_protect) $client->expire($flag, 6); 

echo("process");
for($i=0;$i<5;$i++){
    sleep(1);
    echo('.');
    }
echo("\n");
$client->del($flag);
if(!$client->get($flag)) echo("unlocked\n");

