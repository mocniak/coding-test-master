<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\RatingPopup;
use App\Entity\User;
use App\Repository\RatingPopupRepository;
use App\Repository\UserRepository;
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

    public function __construct(
        KernelInterface $kernel,
        ManagerRegistry $managerRegistry,
        UserRepository $userRepository,
        RatingPopupRepository $popupRepository
    ) {
        $this->kernel = $kernel;
        $this->managerRegistry = $managerRegistry;
        $this->userRepository = $userRepository;
        $this->popupRepository = $popupRepository;
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
        $ratingPopup = $this->popupRepository->findOneBy(['userId' => $userId]);
        if ($ratingPopup === null) {
            $ratingPopup = new RatingPopup($userId);
            $manager = $this->managerRegistry->getManagerForClass(RatingPopup::class);
            $manager->persist($ratingPopup);
        }
        for ($i = 0; $i < $attendanceCount; $i++) {
            $ratingPopup->userAttendedAClass(new \DateTimeImmutable());
        }
        $manager = $this->managerRegistry->getManagerForClass(RatingPopup::class);
        $manager->flush();
    }

    /**
     * @When I ask if the popup should be showed to :username
     */
    public function iAskIfThePopupShouldBeShowedToMe(string $username)
    {
        $userId = $this->userRepository->findOneBy(['email' => $username.'@example.com'])->getId();
        $this->response = $this->kernel->handle(Request::create('/api/rating-popup/'.$userId.'/visible', 'GET'));
    }

    /**
     * @Then I see that I should not see a popup
     */
    public function iSeeThatIShouldNotSeeAPopup()
    {
        $result = json_decode($this->response->getContent(), true);
        Assert::false($result['visible']);
    }

    /**
     * @When I dismiss the popup
     */
    public function iDismissThePopup()
    {
        throw new PendingException();
    }

    /**
     * @When I rated my classes
     */
    public function iRatedMyClasses()
    {
        throw new PendingException();
    }
}
