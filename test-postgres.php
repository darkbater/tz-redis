<?php
include 'vendor/autoload.php';
// echo(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// var_dump($_ENV);
// print("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}". $_ENV['PG_USER']. $_ENV['PG_PASSWORD']);

$pdo = new PDO("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}", $_ENV['PG_USER'], $_ENV['PG_PASSWORD']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Создать 4 таблицы на postgresql:
// продукты, категории, статистика, заказы, и заполнить стандартными столбцами на ваш выбор.


try{
    // Таблица продуктов
    $pdo->exec("DROP TABLE IF EXISTS products");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );")) echo "products complete\n";







} catch (PDOException $e) {
    die("Error creating any table: " . $e->getMessage());
}    




// В таблице Заказы добавить колонку с временем покупки.

// Вам необходимо написать триггер на таблицу с заказами:
// при добавлении новой строки в таблицу с заказами, собирать статистику сколько товаров
// и какой категории было куплено за день.

