<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Klass;
use App\Entity\User;
use App\Repository\KlassRepository;
use Behat\Behat\Context\Context;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;

/**
 * This context class contains the definitions of the steps used by the demo
 * feature file. Learn how to get started with Behat and BDD on Behat's website.
 *
 * @see http://behat.org/en/latest/quick_start.html
 */
final class ClassesContext implements Context
{
    /** @var KernelInterface */
    private $kernel;

    /** @var Response|null */
    private $response;

    private KlassRepository $klassRepository;

    private ManagerRegistry $managerRegistry;

    public function __construct(
        KernelInterface $kernel,
        KlassRepository $klassRepository,
        ManagerRegistry $managerRegistry
    ) {
        $this->kernel = $kernel;
        $this->klassRepository = $klassRepository;
        $this->managerRegistry = $managerRegistry;
        $managerRegistry->getManagerForClass(Klass::class)
            ->createQuery('DELETE FROM App\Entity\Klass')
            ->execute()
        ;
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
        $klass->enroll((new User())->setEmail($username.'@example.com'));
    }

    /**
     * @When I open a list of classes
     */
    public function iOpenAListOfClasses()
    {
        $this->response = $this->kernel->handle(Request::create('/api/classes', 'GET'));
    }

    /**
     * @Then I see 1st available class :arg1 is :arg2
     */
    public function iSeeStAvailableClassIs($arg1, $arg2)
    {
        var_dump($this->response->getContent());
    }
}
