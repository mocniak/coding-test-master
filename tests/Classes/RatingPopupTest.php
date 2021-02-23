<?php

namespace App\Tests\Classes;

use App\Common\RealClock;
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
            self::assertFalse($ratingPopup->shouldBeShowed());
        }
    }

    public function testIfUserAttended5TimesAnd24HoursHasPassedSinceTheEndOfTheClassShowThemAPopup(): array
    {
        $clock = new FakeClock();
        $ratingPopup = new RatingPopup($clock);
        for ($i = 0; $i < 5; ++$i) {
            $ratingPopup->userAttendedAClass();
        }
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT24H')));
        self::assertFalse($ratingPopup->shouldBeShowed());
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT1H')));
        self::assertTrue($ratingPopup->shouldBeShowed());

        return [$clock, $ratingPopup];
    }

    /** @depends testIfUserAttended5TimesAnd24HoursHasPassedSinceTheEndOfTheClassShowThemAPopup */
    public function testIfUserDismissedRatingPopupShowItAgainAfter15ClassesAnd24Hours(array $array)
    {
        /**
         * @var FakeClock   $clock
         * @var RatingPopup $ratingPopup
         */
        list($clock, $ratingPopup) = $array;

        $ratingPopup->popupDismissed();
        for ($i = 0; $i < 15; ++$i) {
            $ratingPopup->userAttendedAClass();
            self::assertFalse($ratingPopup->shouldBeShowed());
        }
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT24H')));
        self::assertFalse($ratingPopup->shouldBeShowed());
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT1H')));
        self::assertTrue($ratingPopup->shouldBeShowed());

        return [$clock, $ratingPopup];
    }

    /** @depends testIfUserAttended5TimesAnd24HoursHasPassedSinceTheEndOfTheClassShowThemAPopup */
    public function testIfUserSubmittedRatingShowItAgainAfter25ClassesAnd24Hours(array $array)
    {
        /**
         * @var FakeClock   $clock
         * @var RatingPopup $ratingPopup
         */
        list($clock, $ratingPopup) = $array;

        $ratingPopup->ratingSubmitted();
        for ($i = 0; $i < 25; ++$i) {
            $ratingPopup->userAttendedAClass();
            self::assertFalse($ratingPopup->shouldBeShowed());
        }
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT24H')));
        self::assertFalse($ratingPopup->shouldBeShowed());
        $clock->setCurrentTime($clock->getCurrentTime()->add(new \DateInterval('PT1H')));
        self::assertTrue($ratingPopup->shouldBeShowed());

        return [$clock, $ratingPopup];
    }

    /** @depends testIfUserDismissedRatingPopupShowItAgainAfter15ClassesAnd24Hours */
    public function testIfUserDismissedPopupTwoTimesItShouldNotBeVisibleAnymore(array $array)
    {
        /**
         * @var FakeClock   $clock
         * @var RatingPopup $ratingPopup
         */
        list($clock, $ratingPopup) = $array;

        $ratingPopup->popupDismissed();
        self::assertFalse($ratingPopup->shouldBeShowed());
        $ratingPopup->userAttendedAClass();
        self::assertFalse($ratingPopup->shouldBeShowed());
    }

    /** @depends testIfUserSubmittedRatingShowItAgainAfter25ClassesAnd24Hours */
    public function testIfUserRatedPopupTwoTimesItShouldNotBeVisibleAnymore(array $array)
    {
        /**
         * @var FakeClock   $clock
         * @var RatingPopup $ratingPopup
         */
        list($clock, $ratingPopup) = $array;

        $ratingPopup->popupDismissed();
        self::assertFalse($ratingPopup->shouldBeShowed());
        $ratingPopup->userAttendedAClass();
        self::assertFalse($ratingPopup->shouldBeShowed());
    }
}
