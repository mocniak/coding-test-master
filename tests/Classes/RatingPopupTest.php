<?php

namespace App\Tests\Classes;

use App\Common\RealClock;
use App\Entity\Klass;
use App\Entity\RatingPopup;
use App\Tests\Stub\FakeClock;
use PHPUnit\Framework\TestCase;

class RatingPopupTest extends TestCase
{
    public function testIfUserAttended4TimesDoesNotShowThemAPopup()
    {
        $ratingPopup = new RatingPopup(new RealClock());
        for ($i = 0; $i < 4; ++$i) {
            $ratingPopup->userAttendedAClass();
        }
        self::assertFalse($ratingPopup->shouldBeShowed());
    }

    public function testIfUserAttended5TimesAnd24HoursHasPassedSinceTheEndOfTheClassShowThemAPopup()
    {
        $clock = new FakeClock();
        $ratingPopup = new RatingPopup($clock);
        for ($i = 0; $i < 5; ++$i) {
            $ratingPopup->userAttendedAClass();
        }
        $classDuration = new \DateInterval(Klass::DURATION);
        $clock->setCurrentTime((new \DateTimeImmutable('+23 hour'))->add($classDuration));
        self::assertFalse($ratingPopup->shouldBeShowed());
        $clock->setCurrentTime((new \DateTimeImmutable('+24 hour'))->add($classDuration));
        self::assertTrue($ratingPopup->shouldBeShowed());
    }
}
