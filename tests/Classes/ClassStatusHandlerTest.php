<?php

namespace App\Tests\Classes;

use App\Classes\ClassStatusHandler;
use App\Entity\Klass;
use App\Entity\User;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ClassStatusHandlerTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;
    private Klass $klass;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->entityManager = self::$container->get(EntityManagerInterface::class);
        $this->klass = (new Klass())
            ->setStartsAt(new DateTime('2013-04-27 17:00'))
            ->setTopic('Class topic')
        ;
        $this->entityManager->persist($this->klass);
        $this->entityManager->flush();
    }

    public function testStatusChanges(): void
    {
        self::assertSame(ClassStatusHandler::SCHEDULED, $this->klass->getStatus());

        $user = $this->createUser();
        $this->klass->addStudent($user);
        $this->entityManager->flush();

        self::assertSame(ClassStatusHandler::BOOKED, $this->klass->getStatus());

        $this->klass->removeStudent($user);

        $this->entityManager->flush();

        self::assertSame(ClassStatusHandler::SCHEDULED, $this->klass->getStatus());

        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());
        $this->klass->addStudent($this->createUser());
        $this->entityManager->flush();

        self::assertSame(ClassStatusHandler::FULL, $this->klass->getStatus());

        $this->klass->getStudents()->clear();
        $this->klass->setStartsAt(new DateTime('-1 day'));
        $this->entityManager->flush();

        self::assertSame(ClassStatusHandler::CANCELLED, $this->klass->getStatus());
    }

    private function createUser(): User
    {
        $suffix = rand(100, 999);
        $user = (new User())
            ->setEmail("user-$suffix@lingoda.com")
            ->setPassword('…')
        ;
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
