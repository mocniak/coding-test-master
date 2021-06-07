<?php

namespace App\Tests\Stub;

use App\Common\Clock;

class FakeClock implements Clock
{
    private ?\DateTimeImmutable $currentTime;

    public function __construct()
    {
        $this->currentTime = null;
    }

    public function setCurrentTime(\DateTimeImmutable $currentTime)
    {
        $this->currentTime = $currentTime;
    }

    public function getCurrentTime(): \DateTimeImmutable
    {
        return $this->currentTime ?? new \DateTimeImmutable();
    }
}
