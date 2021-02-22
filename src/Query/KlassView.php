<?php

namespace App\Query;

class KlassView
{
    public int $id;
    public \DateTimeImmutable $startsAt;
    public string $topic;

    public function __construct(int $id, \DateTimeImmutable $startsAt, string $topic)
    {
        $this->id = $id;
        $this->startsAt = $startsAt;
        $this->topic = $topic;
    }
}
