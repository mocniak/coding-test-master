<?php

namespace App\Controller;

use App\Common\Clock;
use App\Entity\Klass;
use App\Entity\RatingPopup;
use App\Entity\User;
use App\Query\KlassView;
use App\Repository\RatingPopupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/rating-popup")
 */
class RatingPopupController extends AbstractController
{
    /**
     * @Route("/{id}/class_attended", methods={"POST"})
     */
    public function classAttended(
        User $user,
        RatingPopupRepository $popupRepository,
        EntityManagerInterface $entityManager,
        Clock $clock
    ): Response {
        $ratingPopup = $popupRepository->findOneBy(['userId' => $user->getId()]);
        if ($ratingPopup === null) {
            $ratingPopup = new RatingPopup($user->getId());
            $entityManager->persist($ratingPopup);
        }
        $ratingPopup->userAttendedAClass($clock->getCurrentTime());

        $entityManager->flush();

        return new Response(null, Response::HTTP_ACCEPTED);
    }

    /**
     * @Route("/{userId}/visible", methods={"GET"})
     */
    public function visible(RatingPopup $ratingPopup, Clock $clock): JsonResponse
    {
        return $this->apiJson(['visible' => $ratingPopup->shouldBeShowed($clock->getCurrentTime())]);
    }
}
