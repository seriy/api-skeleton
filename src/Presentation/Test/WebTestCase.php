<?php

declare(strict_types=1);

namespace App\Presentation\Test;

use Hautelook\AliceBundle\PhpUnit\RefreshDatabaseTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase as BaseTestCase;
use function getenv;
use function json_decode;
use function json_encode;
use function sprintf;

class WebTestCase extends BaseTestCase
{
    use RefreshDatabaseTrait;

    protected function login(string $username, string $password): array
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/login',
            [],
            [],
            [],
            json_encode([
                'username' => $username,
                'password' => $password,
            ])
        );

        return json_decode($client->getResponse()->getContent(), true);
    }

    protected function createClientWithJwt(string $username, string $password): ?KernelBrowser
    {
        $token = $this->login($username, $password)['token'];

        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_AUTHORIZATION' => sprintf('Bearer %s', $token),
        ]);
    }

    protected function createClientWithKey(): ?KernelBrowser
    {
        return static::createClient([], [
            'CONTENT_TYPE' => 'application/json',
            'HTTP_X_API_KEY' => sha1(sha1(date('Y-m-d H')).getenv('KEY_SALT')),
        ]);
    }
}
