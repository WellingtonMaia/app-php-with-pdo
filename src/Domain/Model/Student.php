<?php

namespace Alura\Pdo\Domain\Model;

use DateTimeInterface;
use DomainException;

class Student
{
    private ?int $id;
    private string $name;
    private DateTimeInterface $birthDate;
    /** @var Phone[] */
    private array $phones = [];

    public function __construct(?int $id, string $name, DateTimeInterface $birthDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->birthDate = $birthDate;
    }

    public function defineId(int $id): void
    {
        if (!is_null($this->id)) {
            throw new DomainException('Do you only can define the ID once');
        }

        $this->id = $id;
    }

    public function id(): ?int
    {
        return $this->id;
    }

    public function changeName(string $name): void
    {
        $this->name = $name;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function birthDate(): DateTimeInterface
    {
        return $this->birthDate;
    }

    public function age(): int
    {
        return $this->birthDate
            ->diff(new \DateTimeImmutable())
            ->y;
    }

    public function addPhone(Phone $phone): void
    {
        $this->phones[] = $phone;
    }

    /** @return Phone[] */
    public function phones(): array
    {
        return $this->phones;
    }
}
