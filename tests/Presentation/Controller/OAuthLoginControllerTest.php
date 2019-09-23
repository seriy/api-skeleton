<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class OAuthLoginControllerTest extends WebTestCase
{
    public function testUnsupportedProvider()
    {
        $client = $this->createClientWithKey();
        $client->request('POST', '/v1.0/connect/twitter');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":1016', $client->getResponse()->getContent());
    }

    public function testCodeNotFound()
    {
        $client = $this->createClientWithKey();
        $client->request('POST', '/v1.0/connect/google');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":7', $client->getResponse()->getContent());
    }

    public function testBadCredentials()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/connect/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'code' => '4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU',
                    ],
                ],
            ])
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":3', $client->getResponse()->getContent());
    }

    public function testSuccess()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/connect/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'attributes' => [
                        'code' => '4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU',
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"token"', $client->getResponse()->getContent());
    }
}
