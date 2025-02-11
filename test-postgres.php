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

/// [ ] создаём таблицы
/// [v] добавляем тригер
/// [ ] добавляем в статистику инфу по заказам
/// [ ] создаём таблицы
/// [ ] создаём таблицы
/// [ ] создаём таблицы
/// [ ] создаём таблицы


$pdo->exec("CREATE TABLE IF NOT EXISTS test (
                id SERIAL PRIMARY KEY,
                test VARCHAR(255) NOT NULL);");

try{
    
    // Таблица категорий
    $pdo->exec("DROP TABLE IF EXISTS categories CASCADE;");
    $pdo->exec("CREATE TABLE IF NOT EXISTS categories (
                    id SERIAL PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    description TEXT);");


    // Таблица продуктов
    $pdo->exec("DROP TABLE IF EXISTS products");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS products (
                id SERIAL PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                description TEXT,
                price DECIMAL(10, 2) NOT NULL,
                category_id INT NOT NULL,
                CONSTRAINT fk_category
                    FOREIGN KEY (category_id)
                    REFERENCES categories(id)
                    ON DELETE CASCADE

                -- category_id INT REFERENCES categories(id) ON DELETE CASCADE
                );")) echo "products complete\n";


    // Таблица заказов
    // TODO таблица заказов
    $pdo->exec("DROP TABLE IF EXISTS orders");
    if($pdo->exec("CREATE TABLE IF NOT EXISTS orders (
                    id SERIAL PRIMARY KEY,
                    -- user_id INT NOT NULL,
                    total_price DECIMAL(10, 2) NOT NULL,
                    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    status VARCHAR(50) DEFAULT 'pending',
                    purchase_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                );")) echo "orders complete\n";


// Триггер на заказы
// $pdo->exec("DROP TABLE IF EXISTS products");
$pdo->exec("DROP FUNCTION IF EXISTS upset_statistics");
$pdo->exec("DROP FUNCTION IF EXISTS upset_statistic");
if($pdo->exec("CREATE FUNCTION upset_statistics()
                        RETURNS trigger AS
                        $$
                        BEGIN
                            INSERT INTO test(test) values('test');
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

// AFTER INSERT OR UPDATE OR DELETE ON personal_salary
//     FOR EACH ROW EXECUTE PROCEDURE salary_audit();
// $pdo->exec("INSERT INTO orders(")




} catch (PDOException $e) {
    die("Error creating any table: " . $e->getMessage());
}    




// В таблице Заказы добавить колонку с временем покупки.

// Вам необходимо написать триггер на таблицу с заказами:
// при добавлении новой строки в таблицу с заказами, собирать статистику сколько товаров
// и какой категории было куплено за день.



# https://timeweb.cloud/tutorials/postgresql/postgresql-triggery-sozdanie-udalenie-primery

# https://sql-ex.ru/blogs/?/Triggery_v_PostgreSQL_chast_2.html
// CREATE OR REPLACE FUNCTION save_changes()
//     RETURNS TRIGGER
//     LANGUAGE PLPGSQL
//     AS
//     $$
//     BEGIN
//         IF (NEW.book_name <> OLD.book_name) OR (NEW.price <> OLD.price) THEN
//         INSERT INTO books_log(book_id,book_name,price,change_date)
//         VALUES(OLD.id,OLD.book_name,OLD.price, now());
//         END IF;

// 	RETURN NEW;
//     END;
//     $$

# https://selectel.ru/blog/tutorials/trigger-postgresql/
//  ?? CREATE FUNCTION <имя_функции>()
// RETURNS trigger AS
// $$
//     BEGIN
//         <тело функции>
//         RETURN [NEW|OLD|NULL]
//     END;
// $$
// LANGUAGE plpgsql;

// CREATE OR REPLACE TRIGGER salary_check BEFORE UPDATE OF salary ON personal_salary
    // FOR EACH ROW    WHEN (OLD.salary IS DISTINCT FROM NEW.salary)    EXECUTE FUNCTION salary_check();

//     -- Создаем таблицу зарплат сотрудников и таблицу для аудита
// CREATE TABLE personal_salary (
//     emp_name        text NOT NULL,
//     salary          integer
// );
// CREATE TABLE personal_salary_audit (
//     operation	char(1)		NOT NULL,
//     date 		timestamp	NOT NULL,
//     userid		text		NOT NULL,
//     emp_name		text		NOT NULL,
//     salary		integer
// );
// -- Создаем триггерную функцию
// CREATE OR REPLACE FUNCTION salary_audit() RETURNS TRIGGER AS $salary_audit$
//     BEGIN
//         -- Добавляем строку в personal_salary_audit, которая отражает операцию, выполняемую в personal_salary
//         -- Для определения типа операции применяется специальная переменная TG_OP
//         IF (TG_OP = 'DELETE') THEN
//             INSERT INTO personal_salary_audit SELECT 'D', now(), user, OLD.*;
//         ELSIF (TG_OP = 'UPDATE') THEN
//             INSERT INTO personal_salary_audit SELECT 'U', now(), user, NEW.*;
//         ELSIF (TG_OP = 'INSERT') THEN
//             INSERT INTO personal_salary_audit SELECT 'I', now(), user, NEW.*;
//         END IF;
//         RETURN NULL; -- Возвращаемое значение для триггеров AFTER игнорируется
//     END;
// $salary_audit$ LANGUAGE plpgsql;
// -- Создаем триггер уровня строки:
// CREATE TRIGGER salary_audit
// AFTER INSERT OR UPDATE OR DELETE ON personal_salary
//     FOR EACH ROW EXECUTE PROCEDURE salary_audit();
// -- Проверим, что все работает, как ожидаем, добавим строку в таблицу personal_salary
// INSERT INTO personal_salary VALUES  ('test', '500');
// -- И посмотрим на результат:
// SELECT * FROM personal_salary;
// emp_name | test
// salary   | 500
// SELECT * FROM personal_salary_audit;  
// operation | I
// date      | 2024-06-19 14:07:14.996449
// userid    | postgres
// emp_name  | test
// salary    | 500

# https://habr.com/ru/companies/otus/articles/857396/
// CREATE OR REPLACE FUNCTION log_order_changes()
// RETURNS TRIGGER AS $$
// BEGIN
//     INSERT INTO order_history (order_id, changed_quantity, change_time)
//     VALUES (OLD.order_id, OLD.quantity, NOW());
//     RETURN NEW;
// END;
// $$ LANGUAGE plpgsql;

# https://help.sweb.ru/postgresql-triggery-sozdanie-udalenie-primery_1396.html
// Базовый синтаксис для создания триггерной функции:

// CREATE FUNCTION имя_функции()
// RETURNS trigger AS
// $$
// BEGIN
//     тело функции;
//     RETURN [NEW|OLD|NULL];
// END;
// $$
// LANGUAGE plpgsql;



# https://zxmd.wordpress.com/2010/11/04/triggers-pgsql/

// CREATE OR REPLACE FUNCTION add_to_log() RETURNS TRIGGER AS $$
// DECLARE
//     mstr varchar(30);
//     astr varchar(100);
//     retstr varchar(254);
// BEGIN
//     IF    TG_OP = 'INSERT' THEN
//         astr = NEW.name;
//         mstr := 'Add new user ';
//         retstr := mstr || astr;
//         INSERT INTO logs(text,added) values (retstr,NOW());
//         RETURN NEW;
//     ELSIF TG_OP = 'UPDATE' THEN
//         astr = NEW.name;
//         mstr := 'Update user ';
//         retstr := mstr || astr;
//         INSERT INTO logs(text,added) values (retstr,NOW());
//         RETURN NEW;
//     ELSIF TG_OP = 'DELETE' THEN
//         astr = OLD.name;
//         mstr := 'Remove user ';
//         retstr := mstr || astr;
//         INSERT INTO logs(text,added) values (retstr,NOW());
//         RETURN OLD;
//     END IF;
// END;
// $$ LANGUAGE plpgsql;




class order{

}