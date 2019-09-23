<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function getenv;

class AddOAuthProviderControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'POST',
            '/v1.0/users/1/providers/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '1',
                    'attributes' => [
                        'code' => '',
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
            'POST',
            '/v1.0/users/1/providers/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '2',
                    'attributes' => [
                        'code' => '4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU',
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
            'POST',
            '/v1.0/users/1/providers/google',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
                    'id' => '1',
                    'attributes' => [
                        'code' => '4/rQEr5DHX11CFp8VruEMecYazHJOZRe_Ea8vIVCMqkGKKaDK2oChpU9RD2830oJm_lEc_Te8jrFJiBW5ga5ygOGU',
                    ],
                ],
            ])
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
