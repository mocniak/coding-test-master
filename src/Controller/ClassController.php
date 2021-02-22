<?php

namespace App\Controller;

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
        return $this->apiJson($klassListQuery->getAll());
    }

    /**
     * @Route("/{id}/book", methods={"POST"})
     */
    public function book(Klass $klass, EntityManagerInterface $entityManager): JsonResponse
    {
        if ($klass->status() === Klass::FULL) {
            throw new BadRequestHttpException('Class is full');
        }

        $klass->enroll($this->ensureUser());

        $entityManager->flush();

        return $this->apiJson(KlassView::fromKlass($klass));
    }

    /**
     * @Route("/{id}/book", methods={"DELETE"})
     */
    public function cancel(Klass $klass, EntityManagerInterface $entityManager): JsonResponse
    {
        $klass->removeStudent($this->ensureUser());

        $entityManager->flush();

        return $this->apiJson(KlassView::fromKlass($klass));
    }
}
