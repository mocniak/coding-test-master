<?php

namespace App\Tests\Classes;

use App\Classes\ClassStatusHandler;
use App\Entity\Klass;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class KlassTest extends TestCase
{
    private EntityManagerInterface $entityManager;
    private Klass $klass;

    protected function setUp(): void
    {
        $this->klass = (new Klass('Class topic', new \DateTimeImmutable('2013-04-27 17:00')));
    }

    public function testPropertiesAssignment()
    {
        self::assertEquals('Class topic', $this->klass->topic());
    }

    public function testStatusChanges(): void
    {
        self::assertSame(ClassStatusHandler::SCHEDULED, $this->klass->getStatus());

        $user = $this->createUser();
        $this->klass->addStudent($user);

        self::assertSame(ClassStatusHandler::BOOKED, $this->klass->getStatus());

        $this->klass->removeStudent($user);

        self::assertSame(ClassStatusHandler::SCHEDULED, $this->klass->getStatus());

        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());

        self::assertSame(ClassStatusHandler::FULL, $this->klass->getStatus());

        $this->klass->getStudents()->clear();
        $this->klass->setStartsAt(new DateTime('-1 day'));

        self::assertSame(ClassStatusHandler::CANCELLED, $this->klass->getStatus());
    }

    private function createUser(): User
    {
        $suffix = rand(100, 999);
        $user = (new User())
            ->setEmail("user-$suffix@lingoda.com")
            ->setPassword('…')
        ;

        return $user;
    }
}
