<?php
include 'vendor/autoload.php';
// echo(__DIR__);
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();
// var_dump($_ENV);
// print("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}". $_ENV['PG_USER']. $_ENV['PG_PASSWORD']);

$pdo = new PDO("pgsql:host={$_ENV['PG_HOST']};dbname={$_ENV['PG_DBNAME']}", $_ENV['PG_USER'], $_ENV['PG_PASSWORD']);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$pdo->exec("CREATE TABLE IF NOT EXISTS test (
                id SERIAL PRIMARY KEY,
                test VARCHAR(255) NOT NULL);");

try{
    // Таблица категорий
    // $pdo->exec("DROP TABLE IF EXISTS categories CASCADE;");
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
                    id SERIAL PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    description TEXT);");

    echo('cat');
    // Таблица продуктов
    // $pdo->exec("DROP TABLE IF EXISTS products CASCADE");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                category_id INT REFERENCES categories(id) ON DELETE CASCADE
                );")) echo "products complete\n";

    // Таблица заказов
    $pdo->exec("DROP TABLE IF EXISTS orders CASCADE");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS orders (
                    id SERIAL PRIMARY KEY,
                    -- user_id INT NOT NULL,
                    product_id INT REFERENCES products(id),
                    count INT NOT NULL,
                    total_price DECIMAL(10, 2) NOT NULL,
                    status VARCHAR(50) DEFAULT 'pending',
                    purchase_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );")) echo "orders complete\n";


    // Таблица статистики
    $pdo->exec("DROP TABLE IF EXISTS statistics CASCADE");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS statistics (
                id SERIAL PRIMARY KEY,
                category_id INT REFERENCES categories(id),
                count INT NOT NULL,
                date DATE DEFAULT CURRENT_DATE,
                UNIQUE (category_id, date)  
                );")) echo "statistics complete\n";


    // Триггер на заказы
    // $pdo->exec("DROP TABLE IF EXISTS products");
    $pdo->exec("DROP FUNCTION IF EXISTS upset_statistics");
    if($pdo->exec("CREATE FUNCTION upset_statistics()
                        RETURNS trigger AS
                        $$
                        DECLARE
                            _category INT;
                            _date DATE;

                        BEGIN
                            --- id категории из продуктов
                            SELECT category_id INTO _category
                            FROM products
                            WHERE id = NEW.product_id;

                            _date=NEW.purchase_time;

                            INSERT INTO statistics (category_id, date, count)
                            VALUES (_category, _date, NEW.count)
                            ON CONFLICT (category_id, date)
                            DO UPDATE SET 
                                count = statistics.count + NEW.count;

                            RETURN NEW;
                        END;
                        $$
                        LANGUAGE plpgsql;
                        ")) echo "trigger appended\n";

    $pdo->exec("DROP TRIGGER IF EXISTS order_statistic_upset_trigger ON orders;");
    if($pdo->exec("CREATE TRIGGER order_statistic_upset_trigger
                    AFTER INSERT ON orders
                    FOR EACH ROW EXECUTE FUNCTION upset_statistics();
                        ")) echo "trigger appended\n";

} catch (PDOException $e) {
    die("Error creating any table: " . $e->getMessage());
}    

die("complete");

