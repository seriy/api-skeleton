<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function json_encode;

class AddUserControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/register',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'email' => '',
                        'username' => '',
                        'password' => '',
                        'firstName' => '',
                        'lastName' => '',
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
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/register',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'email' => 'user@example.com',
                        'username' => 'user',
                        'password' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', // password
                        'firstName' => 'first',
                        'lastName' => 'last',
                    ],
                ],
            ])
        );

        $this->assertEquals(201, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
