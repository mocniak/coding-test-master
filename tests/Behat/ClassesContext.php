<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Klass;
use App\Entity\User;
use App\Repository\KlassRepository;
use App\Repository\UserRepository;
use Behat\Behat\Context\Context;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

final class ClassesContext implements Context
{
    private KernelInterface $kernel;

    private ?Response $response;

    private KlassRepository $klassRepository;

    private ManagerRegistry $managerRegistry;

    private UserRepository $userRepository;

    public function __construct(
        KernelInterface $kernel,
        KlassRepository $klassRepository,
        ManagerRegistry $managerRegistry,
        UserRepository $userRepository
    ) {
        $this->kernel = $kernel;
        $this->klassRepository = $klassRepository;
        $this->managerRegistry = $managerRegistry;
        $managerRegistry->getManagerForClass(Klass::class)
            ->createQuery('DELETE FROM App\Entity\Klass AS k')
            ->execute()
        ;
        $managerRegistry->getManagerForClass(Klass::class)
            ->createQuery('DELETE FROM App\Entity\User AS u')
            ->execute()
        ;
        $this->userRepository = $userRepository;
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        if ($this->response === null) {
            throw new \RuntimeException('No response received');
        }
    }

    /**
     * @Given there is a class :classTopic starting at :classBeginningTime
     */
    public function thereIsAClassStartingAt(string $classTopic, string $classBeginningTime)
    {
        $class = new Klass($classTopic, new \DateTimeImmutable($classBeginningTime));
        $manager = $this->managerRegistry->getManagerForClass(Klass::class);
        $manager->persist($class);
        $manager->flush();
    }

    /**
     * @Given user :username attends to class :classTopic
     */
    public function userAttendsToClass(string $username, string $classTopic)
    {
        $klass = $this->klassRepository->findOneBy(['topic' => $classTopic]);
        $newStudent = (new User())->setEmail($username.'@example.com')->setPassword('testPass');
        $manager = $this->managerRegistry->getManagerForClass(User::class);
        $manager->persist($newStudent);
        $klass->enroll($newStudent);
        $manager->flush();
        $manager->clear();
    }

    /**
     * @Given nobody attends to class :classTopic
     */
    public function nobodyAttendsToClass(string $classTopic)
    {
        //intentionally left blank
    }

    /**
     * @When I open a list of classes
     */
    public function iOpenAListOfClasses()
    {
        $this->response = $this->kernel->handle(Request::create('/api/classes', 'GET'));
    }

    /**
     * @Then I see 1st available class :parameter is :expectedValue
     */
    public function iSeeStAvailableClassIs($parameter, $expectedValue)
    {
        if (preg_match('/\d{4}-\d{2}-\d{2}/', $expectedValue)) {
            $expectedValue = (new \DateTimeImmutable($expectedValue))->format(\DateTimeInterface::ATOM);
        }
        Assert::eq(json_decode($this->response->getContent(), true)[0][$parameter], $expectedValue);
    }

    /**
     * @Then I see that :username is attending to :classTopic
     */
    public function iSeeThatIsAttendingTo(string $username, string $classTopic)
    {
        $classes = json_decode($this->response->getContent(), true);
        $class = array_filter($classes, function (array $class) use ($classTopic) {
            return $class['topic'] === $classTopic;
        })[0];
        $returnedStudents = $class['students'];
        $expectedStudentId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        $expected = ['id' => $expectedStudentId];
        Assert::inArray($expected, $returnedStudents);
    }

    /**
     * @When I don't see user :username's email address in :classTopic class
     */
    public function iDontSeeUserSEmailAddressInClass($username, $classTopic)
    {
        $classes = json_decode($this->response->getContent(), true);
        $class = array_filter($classes, function (array $class) use ($classTopic) {
            return $class['topic'] === $classTopic;
        })[0];
        foreach ($class['students'] as $student) {
            Assert::keyNotExists($student, 'email');
        }
    }
}
