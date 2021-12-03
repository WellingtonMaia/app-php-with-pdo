<?php

use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require 'vendor/autoload.php';

$connection = ConnectionFactory::createConnection();
$studentRepository = new PdoStudentRepository($connection);

$connection->beginTransaction();

try {
    $oneStudent = new Student(null, "Nico Steppat", new DateTimeImmutable('2000-05-05'));
    $twoStudent = new Student(null, "Ana Silva", new DateTimeImmutable('1998-07-25'));
    $threeStudent = new Student(null, "Rose Farias", new DateTimeImmutable('1988-10-05'));

    $studentRepository->save($oneStudent);
    $studentRepository->save($twoStudent);
    $studentRepository->save($threeStudent);

    $connection->commit();
} catch (PDOException $exception) {
    echo $exception->getMessage();
    $connection->rollBack();
}