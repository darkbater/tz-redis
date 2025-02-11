<?php
include 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// var_dump($_ENV);

echo(__DIR__);
// $PG_HOST    = getenv('PG_HOST');
// $PG_DBNAME  = getenv('PG_DBNAME');
// $PG_USER    = getenv('PG_USER');
// $PG_PASSWORD= getenv('PG_PASSWORD');


// echo($PG_HOST.$PG_DBNAME.$PG_USER.$PG_PASSWORD);

echo "aaa";

// PG_HOST = localhost
// PG_DBNAME = 'test'
// PG_USER = 'test'
// PG_PASSWORD = 'test'

