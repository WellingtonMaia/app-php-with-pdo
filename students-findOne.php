<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;

require 'vendor/autoload.php';

function getById(int $id): array
{
    $pdo = ConnectionFactory::createConnection();
    return $pdo
        ->query("SELECT * FROM students WHERE id = {$id};")
        ->fetch(PDO::FETCH_ASSOC);
}

$studentFound = getById(1);
$student = new Student(
    $studentFound['id'],
    $studentFound['name'],
    new DateTimeImmutable($studentFound['birth_date'])
);
var_dump($student);