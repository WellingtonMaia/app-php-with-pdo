<?php

namespace Alura\Pdo\Infrastructure\Repository;

use Alura\Pdo\Domain\Model\Phone;
use Alura\Pdo\Domain\Model\Student;
use Alura\Pdo\Infrastructure\Persistence\ConnectionFactory;
use DateTimeImmutable;
use PDO;
use PDOStatement;

class PdoStudentRepository implements \Alura\Pdo\Domain\Repository\StudentRepository
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    public function allStudents(): array
    {
        $studentDataList = $this->connection
            ->query("SELECT * FROM students");

        return $this->hydrateStudentList($studentDataList);
    }

    public function studentsBirthAt(DateTimeImmutable $birthDate): array
    {
        $statement = $this->connection->prepare("SELECT * FROM students WHERE birth_date = :birth_date;");
        $statement->bindValue(':birth_date', $birthDate->format('Y-m-d'));
        $statement->execute();

        return $this->hydrateStudentList($statement);
    }

    public function save(Student $student): bool
    {
        if (is_null($student->id())) {
            return $this->insert($student);
        }

        return $this->update($student);
    }

    private function insert(Student $student): bool
    {
        $sqlInsert = "INSERT INTO students (name, birth_date) VALUES (:name, :birth_date);";
        $statement = $this->connection->prepare($sqlInsert);

        $success = $statement->execute([
            ':name' => $student->name(),
            ':birth_date' => $student->birthDate()->format('Y-m-d'),
        ]);

        $student->defineId($this->connection->lastInsertId());

        return $success;
    }

    private function update(Student $student): bool
    {
        $sqlUpdate = "UPDATE students SET name = :name, birth_date = :birth_date WHERE id = :id;";
        $statement = $this->connection->prepare($sqlUpdate);
        $statement->bindValue(':name', $student->name());
        $statement->bindValue(':birth_date', $student->birthDate()->format('Y-m-d'));
        $statement->bindValue(':id', $student->id(), PDO::PARAM_INT);

        return $statement->execute();
    }

    public function delete(Student $student): bool
    {
        $statement = $this->connection->prepare("DELETE FROM students WHERE id = ?;");
        $statement->bindValue(1, $student->id(), PDO::PARAM_INT);

        return  $statement->execute();
    }

    private function hydrateStudentList(PDOStatement $statement): array
    {
        $studentDataList = $statement->fetchAll();
        $studentList = [];

        foreach ($studentDataList as $item) {
            $studentList[] = new Student($item['id'], $item['name'], new DateTimeImmutable($item['birth_date']));
        }

        return $studentList;
    }

//    private function fillPhoneOf(Student $student)
//    {
//        $sqlQuery = "SELECT id, area_code, number FROM phones WHERE student_id = :student_id;";
//        $statement = $this->connection->prepare($sqlQuery);
//        $statement->bindValue(':student_id', $student->id(), PDO::PARAM_INT);
//        $statement->execute();
//
//        $phoneDataList = $statement->fetchAll();
//        foreach ($phoneDataList as $item) {
//            $phone = new Phone(
//              $item['id'],
//              $item['area_code'],
//              $item['number']
//            );
//
//            $student->addPhone($phone);
//        }
//    }

    public function allStudentsWithPhone(): array
    {
        $sqlQuery = "SELECT 
                            students.id,
                            students.name,
                            students.birth_date,
                            phones.id AS phone_id,
                            phones.area_code,
                            phones.number
                     FROM students
                     JOIN phones ON students.id = phones.student_id;";
        $statement = $this->connection->query($sqlQuery);
        $result = $statement->fetchAll();
        $studentList = [];

        foreach ($result as $row) {
            if (!array_key_exists($row['id'], $studentList)) {
                $studentList[$row['id']] = new Student(
                  $row['id'],
                  $row['name'],
                  new DateTimeImmutable($row['birth_date'])
                );
            }
            $phone = new Phone($row['phone_id'], $row['area_code'], $row['number']);
            $studentList[$row['id']]->addPhone($phone);
        }

        return $studentList;
    }


}