<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class RefreshControllerTest extends WebTestCase
{
    public function testBadCredentials()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/refresh',
            [],
            [],
            [],
            json_encode([
                'refresh_token' => 'token',
            ])
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":3', $client->getResponse()->getContent());
    }
}
