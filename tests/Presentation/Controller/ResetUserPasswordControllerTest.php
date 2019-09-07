<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class ResetUserPasswordControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/reset/password',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'email' => '',
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
            '/v1.0/reset/password',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'email' => 'username@example.com',
                    ],
                ],
            ])
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
