<?php

namespace App\Controller;

use App\Classes\ClassStatusHandler;
use App\Entity\Klass;
use App\Query\KlassListQuery;
use App\Query\KlassView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/classes")
 */
class ClassController extends AbstractController
{
    /**
     * @Route("", methods={"GET"})
     */
    public function all(KlassListQuery $klassListQuery): JsonResponse
    {
        return new JsonResponse(array_map(function (KlassView $klass): array {
            return [
                'id' => $klass->id,
                'startsAt' => $klass->startsAt->format(\DateTimeInterface::ISO8601),
                'status' => 'scheduled',
                'topic' => $klass->topic,
                'students' => $klass->studentIds,
                ];
        }, $klassListQuery->getAll()));
    }

    /**
     * @Route("/{id}/book", methods={"POST"})
     */
    public function book(Klass $klass, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($klass->getStatus() === ClassStatusHandler::FULL) {
            throw new BadRequestHttpException('Class is full');
        }

        $klass->enroll($this->ensureUser());

        $entityManager->flush();

        return $this->apiJson($klass);
    }

    /**
     * @Route("/{id}/book", methods={"DELETE"})
     */
    public function cancel(Klass $klass, EntityManagerInterface $entityManager): JsonResponse
    {
        $klass->removeStudent($this->ensureUser());

        $entityManager->flush();

        return $this->apiJson($klass);
    }
}
