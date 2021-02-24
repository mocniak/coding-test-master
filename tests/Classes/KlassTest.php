<?php

namespace App\Tests\Classes;

use App\Entity\Klass;
use App\Entity\User;
use App\Exception\StudentEnrolledToFullKlassException;
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

    public function testKlassCanEnrollNoMoreThanFourStudents()
    {
        $klass = new Klass('Class topic', new \DateTimeImmutable('2013-04-27 17:00'));
        $klass->enroll(new User()); //1
        $klass->enroll(new User()); //2
        $klass->enroll(new User()); //3
        $klass->enroll(new User()); //4
        $this->expectException(StudentEnrolledToFullKlassException::class);
        $klass->enroll(new User()); //too much
    }

    /** @dataProvider studentsTimesAndKlassStatuses */
    public function testKlassHasEndedStatusWhenItHadAttendeesAndItIsFinished(
        string $studentCount,
        string $startedAt,
        string $expectedStatus
    ) {
        $klass = new Klass('Class topic', new \DateTimeImmutable($startedAt));
        for ($i = 0; $i < $studentCount; ++$i) {
            $klass->enroll(new User());
        }
        $this->assertEquals($expectedStatus, $klass->status());
    }
    public function studentsTimesAndKlassStatuses(): array
    {
        return [
            [0, '+3 days', Klass::SCHEDULED],
            [0, '+1 day', Klass::CANCELLED],
            [0, '60 minutes ago', Klass::CANCELLED],
            [0, '10 days ago', Klass::CANCELLED],
            [1, '59 minutes ago', Klass::BOOKED],
            [4, '59 minutes ago', Klass::FULL],
            [1, '60 minutes ago', Klass::ENDED],
        ];
    }
}
