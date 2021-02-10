<?php


namespace App\Controller;

use App\Entity\ClassRating;
use App\Entity\Klass;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
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
        $payload = $this->getRequestPayload();

        $classRating = new ClassRating($class, $payload['rating']);

        $violations = $validator->validate($classRating);
        if (count($violations) > 0) {
            return $this->json($violations, 400);
        }

        $entityManager->persist($classRating);
        $entityManager->flush();

        return $this->apiJson($classRating);
    }
}
