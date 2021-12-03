<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;

require 'vendor/autoload.php';

$pdo = ConnectionFactory::createConnection();

$queryDelete = "DELETE FROM students WHERE id = ?;";
$preparedStatement = $pdo->prepare($queryDelete);
$preparedStatement->bindValue(1, 7, PDO::PARAM_INT);
var_dump($preparedStatement->execute());