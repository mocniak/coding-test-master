<?php

namespace App\Query;

use App\Entity\Klass;
use App\Entity\User;
use Symfony\Component\Serializer\Annotation\Groups;

class KlassView
{
    private int $id;
    private \DateTimeImmutable $startsAt;
    private string $topic;
    private array $students;
    private string $status;

    public function __construct(int $id, \DateTimeImmutable $startsAt, string $topic, string $status, array $students)
    {
        $this->id = $id;
        $this->startsAt = $startsAt;
        $this->topic = $topic;
        $this->students = $students;
        $this->status = $status;
    }

    public static function fromKlass(Klass $klass): self
    {
        return new self(
            $klass->id(),
            $klass->startsAt(),
            $klass->topic(),
            $klass->status(),
            array_map(function (User $student) {
                return ['id' => $student->getId()];
            }, $klass->students()->toArray())
        );
    }

    /**
     * @Groups("api")
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @Groups("api")
     */
    public function getStartsAt(): \DateTimeImmutable
    {
        return $this->startsAt;
    }

    /**
     * @Groups("api")
     */
    public function getTopic(): string
    {
        return $this->topic;
    }

    /**
     * @Groups("api")
     */
    public function getStudents(): array
    {
        return $this->students;
    }

    /**
     * @Groups("api")
     */
    public function getStatus(): string
    {
        return $this->status;
    }
}
