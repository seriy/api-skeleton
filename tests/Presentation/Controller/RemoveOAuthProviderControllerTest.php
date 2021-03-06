<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function getenv;

class RemoveOAuthProviderControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request('DELETE', '/v1.0/users/1/providers/google');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testMismatch()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'DELETE',
            '/v1.0/users/1/providers/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '2',
                ],
            ])
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testSuccess()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'DELETE',
            '/v1.0/users/1/providers/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '1',
                ],
            ])
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
