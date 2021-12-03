<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;

require 'vendor/autoload.php';

$pdo = ConnectionFactory::createConnection();

$student = new Student(null, 'Patricia Freitas', new DateTimeImmutable('1987-05-05'));

$sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
$statement = $pdo->prepare($sqlInsert);
$statement->bindValue(':name', $student->name());
$statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));

if ($statement->execute()) {
    echo "Student registered". PHP_EOL;
}