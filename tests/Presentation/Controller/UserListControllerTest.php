<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use function getenv;

class UserListControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request('GET', '/v1.0/users?page[limit]=0');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testSuccess()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request('GET', '/v1.0/users?filter[id]=1,2&filter[username]=u&sort=-id,username');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
