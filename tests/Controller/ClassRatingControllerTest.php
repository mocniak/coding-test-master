<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ClassRatingControllerTest extends WebTestCase
{
    public function testRate(): void
    {
        $client = self::createClient();

        $user = self::$container->get(UserRepository::class)->find(1);
        $client->loginUser($user);

        $client->xmlHttpRequest(
            'POST',
            '/api/classes/1/rating',
            [],
            [],
            [],
            '{"rating":4}'
        );

        self::assertResponseIsSuccessful();
        $content = $client->getResponse()->getContent();
        self::assertSame('{"rating":4,"classId":1}', $content);
    }

    public function testRateInvalid(): void
    {
        $client = self::createClient();

        $user = self::$container->get(UserRepository::class)->find(1);
        $client->loginUser($user);

        $client->xmlHttpRequest(
            'POST',
            '/api/classes/1/rating',
            [],
            [],
            [],
            '{"rating":6}'
        );

        self::assertResponseStatusCodeSame(400);
        $content = $client->getResponse()->getContent();
        self::assertContains('This value should be between 1 and 5', $content);
    }
}
