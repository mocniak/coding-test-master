<?php

namespace App\Query;

class KlassView
{
    public int $id;
    public \DateTimeImmutable $startsAt;
    public string $topic;
    public array $studentIds;
    public string $status;

    public function __construct(int $id, \DateTimeImmutable $startsAt, string $topic, string $status, array $studentIds)
    {
        $this->id = $id;
        $this->startsAt = $startsAt;
        $this->topic = $topic;
        $this->studentIds = $studentIds;
        $this->status = $status;
    }
}
