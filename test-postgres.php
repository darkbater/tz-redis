<?php
include 'vendor/autoload.php';
// echo(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// print("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}". $_ENV['PG_USER']. $_ENV['PG_PASSWORD']);

$pdo = new PDO("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}", $_ENV['PG_USER'], $_ENV['PG_PASSWORD']);


// var_dump($_ENV);

// $PG_HOST    = getenv('PG_HOST');
// $PG_DBNAME  = getenv('PG_DBNAME');
// $PG_USER    = getenv('PG_USER');
// $PG_PASSWORD= getenv('PG_PASSWORD');


// echo($PG_HOST.$PG_DBNAME.$PG_USER.$PG_PASSWORD);

// echo "aaa";
