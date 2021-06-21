<?php

namespace App\Controller;

use App\Entity\ClassRating;
use App\Entity\Klass;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/api/classes/{id}/rating")
 */
class ClassRatingController extends AbstractController
{
    /**
     * @Route("", methods={"POST"})
     */
    public function rate(
        Klass $class,
        EntityManagerInterface $entityManager,
        ValidatorInterface $validator
    ): Response {
        try {
            $payload = $this->getRequestPayload();
        } catch (NotEncodableValueException $exception) {
            return new JsonResponse(['error' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
        }
        $violations = $this->validatePayload($payload, $validator);
        if (count($violations) > 0) {
            return $this->json($violations, 400);
        }

        $classRating = new ClassRating($class, $payload['rating']);
        $violations = $validator->validate($classRating);
        if (count($violations) > 0) {
            return $this->json($violations, 400);
        }

        $entityManager->persist($classRating);
        $entityManager->flush();

        return $this->apiJson($classRating);
    }

    private function validatePayload($payload, ValidatorInterface $validator): ConstraintViolationListInterface
    {
        $constraint = new Assert\Collection([
            'rating' => new Assert\Type('integer'),
        ]);
        $groups = new Assert\GroupSequence(['Default', 'custom']);

        return $validator->validate($payload, $constraint, $groups);
    }
}
