<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class ConfirmUserEmailControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithKey();
        $client->request('PATCH', '/v1.0/confirm/email/token');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testSuccess()
    {
        $client = $this->createClientWithKey();
        $client->request('PATCH', '/v1.0/confirm/email/EmailConfirmationTokenEmailConfirmationT');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }
}
