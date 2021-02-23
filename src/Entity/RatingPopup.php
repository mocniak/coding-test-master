<?php

namespace App\Entity;

use App\Common\Clock;

class RatingPopup
{
    const FIRST_DISPLAY_CLASS_COUNT = 5;
    const SECOND_DISPLAY_CLASS_COUNT_IF_DISMISSED = 20;
    const SECOND_DISPLAY_CLASS_COUNT_IF_RATED = 30;

    const STATUS_COUNTING_CLASSES = 'counting';
    const STATUS_WAITING_TO_SHOW_POPUP = 'waiting';
    const STATUS_COUNTING_AFTER_DISMISSED_POPUP = 'dismissed';
    const STATUS_COUNTING_AFTER_RATED = 'rated';

    private int $classCounter;
    private ?\DateTimeImmutable $waitingSince;
    private Clock $clock;
    private string $status;

    public function __construct(Clock $clock)
    {
        $this->classCounter = 0;
        $this->waitingSince = null;
        $this->clock = $clock;
        $this->status = self::STATUS_COUNTING_CLASSES;
    }

    public function userAttendedAClass()
    {
        if ($this->status !== self::STATUS_WAITING_TO_SHOW_POPUP) {
            ++$this->classCounter;
        }
        if ($this->classCounter === self::FIRST_DISPLAY_CLASS_COUNT) {
            $this->waitingSince = $this->clock->getCurrentTime();
            $this->status = self::STATUS_WAITING_TO_SHOW_POPUP;
        } elseif (
            $this->status === self::STATUS_COUNTING_AFTER_DISMISSED_POPUP
            && $this->classCounter === self::SECOND_DISPLAY_CLASS_COUNT_IF_DISMISSED
        ) {
            $this->waitingSince = $this->clock->getCurrentTime();
            $this->status = self::STATUS_WAITING_TO_SHOW_POPUP;
        } elseif (
            $this->status === self::STATUS_COUNTING_AFTER_RATED
            && $this->classCounter === self::SECOND_DISPLAY_CLASS_COUNT_IF_RATED
        ) {
            $this->waitingSince = $this->clock->getCurrentTime();
            $this->status = self::STATUS_WAITING_TO_SHOW_POPUP;
        }
    }

    public function shouldBeShowed(): bool
    {
        if ($this->status === self::STATUS_WAITING_TO_SHOW_POPUP) {
            // DateIntervals are not comparable in php so let's go with timestamps in this single case
            $twentyFourHoursOfWaiting = $this->waitingSince
                ->add(new \DateInterval('PT24H'))
                ->add(new \DateInterval(Klass::DURATION))
                ->getTimestamp()
            ;
            $now = $this->clock->getCurrentTime()->getTimestamp();

            return $now >= $twentyFourHoursOfWaiting;
        }

        return false;
    }

    public function popupDismissed()
    {
        if ($this->status === self::STATUS_WAITING_TO_SHOW_POPUP) {
            $this->status = self::STATUS_COUNTING_AFTER_DISMISSED_POPUP;
        }
    }

    public function ratingSubmitted()
    {
        if ($this->status === self::STATUS_WAITING_TO_SHOW_POPUP) {
            $this->status = self::STATUS_COUNTING_AFTER_RATED;
        }
    }
}
