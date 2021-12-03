<?php

use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;
use Alura\Pdo\Infrastructure\Repository\PdoStudentRepository;

require 'vendor/autoload.php';

$pdo = ConnectionFactory::createConnection();
$repository = new PdoStudentRepository($pdo);

$students = $repository->allStudentsWithPhone();

var_dump($students);