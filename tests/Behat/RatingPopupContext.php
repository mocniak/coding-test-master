<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\User;
use App\Repository\RatingPopupRepository;
use App\Repository\UserRepository;
use App\Tests\Stub\FakeClock;
use Behat\Behat\Context\Context;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

final class RatingPopupContext implements Context
{
    private KernelInterface $kernel;

    private ?Response $response;

    private ManagerRegistry $managerRegistry;

    private UserRepository $userRepository;

    private RatingPopupRepository $popupRepository;

    private FakeClock $fakeClock;

    public function __construct(
        KernelInterface $kernel,
        ManagerRegistry $managerRegistry,
        UserRepository $userRepository,
        RatingPopupRepository $popupRepository,
        FakeClock $fakeClock
    ) {
        $this->kernel = $kernel;
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
        $this->popupRepository = $popupRepository;
        $this->fakeClock = $fakeClock;
    }

    /**
     * @Given there is a user :username
     */
    public function thereIsAUser(string $username)
    {
        $user = (new User())->setEmail($username.'@example.com')->setPassword('somePassword');
        $manager = $this->managerRegistry->getManagerForClass(User::class);
        $manager->persist($user);
        $manager->flush();
    }

    /**
     * @Given :username attended to :attendanceCount classes
     */
    public function attendedToClasses(string $username, int $attendanceCount)
    {
        $userId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        for ($i = 0; $i < $attendanceCount; ++$i) {
            $this->kernel->handle(Request::create('/api/rating-popup/'.$userId.'/class-attended', 'POST'));
        }
    }

    /**
     * @When I ask if the popup should be showed to :username
     */
    public function iAskIfThePopupShouldBeShowedToMe(string $username)
    {
        $userId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        $this->response = $this->kernel->handle(Request::create('/api/rating-popup/'.$userId.'/visible'));
    }

    /**
     * @Then I see that they should not see a popup
     */
    public function iSeeThatIShouldNotSeeAPopup()
    {
        $result = json_decode($this->response->getContent(), true);
        Assert::false($result['visible']);
    }

    /**
     * @Then I see that they should see a popup
     */
    public function iSeeThatTheyShouldSeeAPopup()
    {
        $result = json_decode($this->response->getContent(), true);
        Assert::true($result['visible']);
    }

    /**
     * @Given :hours hours has passed
     */
    public function hoursHasPassed(int $hours)
    {
        $this->fakeClock->setCurrentTime($this->fakeClock->getCurrentTime()->add(new \DateInterval('PT'.$hours.'H')));
    }

    /**
     * @When :username dismisses their popup
     */
    public function userDismissesTheirPopup(string $username)
    {
        $userId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        $this->response = $this->kernel->handle(Request::create('/api/rating-popup/'.$userId.'/dismissed', 'POST'));
    }

    /**
     * @When :username rates their classes
     */
    public function userRatesTheirClasses(string $username)
    {
        $userId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        $this->response = $this->kernel->handle(Request::create('/api/rating-popup/'.$userId.'/rated', 'POST'));
    }
}
