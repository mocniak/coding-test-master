<?php

namespace App\Tests\Classes;

use App\Entity\Klass;
use PHPUnit\Framework\TestCase;

class KlassTest extends TestCase
{
    public function testKlassIsConstructedWithNameAndStartDate()
    {
        $startsAt = new \DateTimeImmutable('2013-04-27 17:00');
        $topic = 'Class topic';
        $klass = new Klass($topic, $startsAt);
        self::assertEquals($topic, $klass->topic());
        self::assertEquals($startsAt, $klass->startsAt());
    }
}
