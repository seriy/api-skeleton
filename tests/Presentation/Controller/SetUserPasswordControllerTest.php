<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class SetUserPasswordControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'PATCH',
            '/v1.0/set/password/PasswordResettingTokenPasswordResettingT',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'newPassword' => '',
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
            'PATCH',
            '/v1.0/set/password/PasswordResettingTokenPasswordResettingT',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'newPassword' => '9d4e1e23bd5b727046a9e3b4b7db57bd8d6ee684', // pass
                    ],
                ],
            ])
        );

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
