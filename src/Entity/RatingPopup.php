<?php

namespace App\Entity;

use App\Repository\RatingPopupRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RatingPopupRepository::class)
 * @ORM\Table
 */
class RatingPopup
{
    const FIRST_DISPLAY_CLASS_COUNT = 5;
    const SECOND_DISPLAY_CLASS_COUNT_IF_DISMISSED = 20;
    const SECOND_DISPLAY_CLASS_COUNT_IF_RATED = 30;

    const STATUS_INITIAL_COUNTING_CLASSES = 'counting';
    const STATUS_WAITING_TO_SHOW_POPUP = 'waiting';
    const STATUS_COUNTING_AFTER_DISMISSED_POPUP = 'dismissed';
    const STATUS_COUNTING_AFTER_RATED = 'rated';

    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     */
    private int $userId;
    /**
     * @ORM\Column(type="integer")
     */
    private int $classCounter;
    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private ?\DateTimeImmutable $waitingSince;
    /**
     * @ORM\Column(type="string", length=10)
     */
    private string $status;

    public function __construct(int $userId)
    {
        $this->userId = $userId;
        $this->classCounter = 0;
        $this->waitingSince = null;
        $this->status = self::STATUS_INITIAL_COUNTING_CLASSES;
    }

    public function userAttendedAClass(\DateTimeImmutable $time)
    {
        if ($this->status !== self::STATUS_WAITING_TO_SHOW_POPUP) {
            ++$this->classCounter;
        }
        if ($this->classCounter === self::FIRST_DISPLAY_CLASS_COUNT) {
            $this->setStatusToWaiting($time);
        } elseif (
            $this->status === self::STATUS_COUNTING_AFTER_DISMISSED_POPUP
            && $this->classCounter === self::SECOND_DISPLAY_CLASS_COUNT_IF_DISMISSED
        ) {
            $this->setStatusToWaiting($time);
        } elseif (
            $this->status === self::STATUS_COUNTING_AFTER_RATED
            && $this->classCounter === self::SECOND_DISPLAY_CLASS_COUNT_IF_RATED
        ) {
            $this->setStatusToWaiting($time);
        }
    }

    public function shouldBeShowed(\DateTimeImmutable $time): bool
    {
        if ($this->status === self::STATUS_WAITING_TO_SHOW_POPUP) {
            // DateIntervals are not comparable in php so let's go with timestamps in this single case
            $twentyFourHoursOfWaiting = $this->waitingSince
                ->add(new \DateInterval('PT24H'))
                ->add(new \DateInterval(Klass::DURATION))
                ->getTimestamp()
            ;
            $time = $time->getTimestamp();

            return $time >= $twentyFourHoursOfWaiting;
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

    private function setStatusToWaiting(\DateTimeImmutable $time): void
    {
        $this->waitingSince = $time;
        $this->status = self::STATUS_WAITING_TO_SHOW_POPUP;
    }
}
