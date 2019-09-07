<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function getenv;

class ChangeUserPasswordControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'PATCH',
            '/v1.0/users/1/password',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '1',
                    'attributes' => [
                        'newPassword' => '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', // pass
                    ],
                ],
            ])
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testMismatch()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'PATCH',
            '/v1.0/users/1/password',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '2',
                    'attributes' => [
                        'newPassword' => '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', // pass
                    ],
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
            'PATCH',
            '/v1.0/users/1/password',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '1',
                    'attributes' => [
                        'currentPassword' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', // password
                        'newPassword' => '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', // pass
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
