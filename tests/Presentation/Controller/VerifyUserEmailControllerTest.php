<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function getenv;

class VerifyUserEmailControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'POST',
            '/v1.0/users/1/email/verify',
            [],
            [],
            [],
            json_encode([
                'data' => [
                    'type' => 'users',
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
            '/v1.0/users/1/email/verify',
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
        $client->request('POST', '/v1.0/users/1/email/verify');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
