<?php
include 'vendor/autoload.php';
// echo(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// var_dump($_ENV);
// print("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}". $_ENV['PG_USER']. $_ENV['PG_PASSWORD']);

$pdo = new PDO("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}", $_ENV['PG_USER'], $_ENV['PG_PASSWORD']);


// Создать 4 таблицы на postgresql:
// продукты, категории, статистика, заказы, и заполнить стандартными столбцами на ваш выбор.

// В таблице Заказы добавить колонку с временем покупки.

// Вам необходимо написать триггер на таблицу с заказами:
// при добавлении новой строки в таблицу с заказами, собирать статистику сколько товаров
// и какой категории было куплено за день.

