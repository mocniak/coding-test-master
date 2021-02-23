<?php

namespace App\Entity;

use App\Common\Clock;

class RatingPopup
{
    const NUMBER_OF_LESSONS_TO_DISPLAY_POPUP = 5;

    private int $classCounter;
    private ?\DateTimeImmutable $fifthClassEndedAt;
    private Clock $clock;

    public function __construct(Clock $clock)
    {
        $this->classCounter = 0;
        $this->fifthClassEndedAt = null;
        $this->clock = $clock;
    }

    public function userAttendedAClass()
    {
        ++$this->classCounter;
        if ($this->classCounter === self::NUMBER_OF_LESSONS_TO_DISPLAY_POPUP) {
            $this->fifthClassEndedAt = $this->clock->getCurrentTime()->add(new \DateInterval(Klass::DURATION));
        }
    }

    public function shouldBeShowed(): bool
    {
        // DateIntervals are not comparable in php so let's go with timestamps in this single case
        $twentyFourHoursAfterFifthClass = $this->fifthClassEndedAt->add(new \DateInterval('PT24H'))->getTimestamp();
        $now = $this->clock->getCurrentTime()->getTimestamp();

        return $this->classCounter >= self::NUMBER_OF_LESSONS_TO_DISPLAY_POPUP && $now >= $twentyFourHoursAfterFifthClass;
    }
}
