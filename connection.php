<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;

require 'vendor/autoload.php';

$pdo = ConnectionFactory::createConnection();

echo 'connected'. PHP_EOL;

//$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('11', '999995555', 1)");
//$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('13', '988884444', 2)");
//$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('18', '998957777', 3)");
//$pdo->exec("INSERT INTO phones (area_code, number, student_id) VALUES ('67', '916789123', 3)");


$createTableSQL = '
    CREATE TABLE IF NOT EXISTS students (
        id INTEGER PRIMARY KEY,
        name TEXT,
        birth_date TEXT
    );

    CREATE TABLE IF NOT EXISTS phones (
        id INTEGER PRIMARY KEY,
        area_code TEXT,
        number TEXT,
        student_id INTEGER,
        FOREIGN KEY(student_id) REFERENCES students(id)
    );
';

$pdo->exec($createTableSQL);