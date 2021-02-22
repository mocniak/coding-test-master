<?php

namespace App\Tests\Classes;

use App\Entity\Klass;
use App\Entity\User;
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

    /** @dataProvider studentsAndKlassStatuses */
    public function testKlassStatus(int $numberOfAttendingStudents, string $expectedStatus)
    {
        $klass = new Klass('Class topic', new \DateTimeImmutable('2013-04-27 17:00'));
        for ($i = 0; $i < $numberOfAttendingStudents; ++$i) {
            $klass->enroll(new User());
        }
        $this->assertEquals($expectedStatus, $klass->status());
    }

    public function studentsAndKlassStatuses(): array
    {
        return [
            [0, Klass::SCHEDULED],
            [1, Klass::BOOKED],
            [3, Klass::BOOKED],
            [4, Klass::FULL],
        ];
    }
}
