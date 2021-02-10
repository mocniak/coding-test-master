<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController as BaseAbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Serializer\Encoder\DecoderInterface;
use Webmozart\Assert\Assert;

abstract class AbstractController extends BaseAbstractController
{
    private RequestStack $requestStack;
    private DecoderInterface $decoder;

    /**
     * @required
     */
    public function setRequestStack(RequestStack $requestStack): void
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @required
     */
    public function setDecoder(DecoderInterface $decoder): void
    {
        $this->decoder = $decoder;
    }

    /**
     * @return array<string, mixed>
     */
    protected function getRequestPayload(): array
    {
        $request = $this->requestStack->getCurrentRequest();
        Assert::notNull($request);

        return $this->decoder->decode($request->getContent(), 'json');
    }

    /**
     * @param mixed $data
     */
    protected function apiJson($data): JsonResponse
    {
        return $this->json($data, JsonResponse::HTTP_OK, [], ['groups' => 'api']);
    }

    protected function ensureUser(): User
    {
        $user = $this->getUser();

        Assert::isInstanceOf($user, User::class);

        return $user;
    }
}
