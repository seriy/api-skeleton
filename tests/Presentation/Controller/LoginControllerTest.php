<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testOptions()
    {
        $client = static::createClient();
        $client->request('OPTIONS', '/v1.0/login');

        $this->assertEquals(204, $client->getResponse()->getStatusCode());
    }

    public function testKeyNotFound()
    {
        $client = static::createClient();
        $client->request(
            'POST',
            '/v1.0/login',
            [],
            [],
            [],
            json_encode([
                'username' => 'username',
                'password' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', // password
            ])
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":1', $client->getResponse()->getContent());
    }

    public function testKeyInvalid()
    {
        $client = static::createClient([], ['HTTP_X_API_KEY' => 'invalid_key']);
        $client->request(
            'POST',
            '/v1.0/login',
            [],
            [],
            [],
            json_encode([
                'username' => 'username',
                'password' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', // password
            ])
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":2', $client->getResponse()->getContent());
    }

    public function testBadCredentials()
    {
        $client = $this->createClientWithKey();
        $client->request(
            'POST',
            '/v1.0/login',
            [],
            [],
            [],
            json_encode([
                'username' => 'user',
                'password' => '5baa61e4c9b93f3f0682250b6cf8331b7ee68fd8', // password
            ])
        );

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":3', $client->getResponse()->getContent());
    }

    public function testJwtNotFound()
    {
        $client = static::createClient();
        $client->request('GET', '/v1.0/users/1');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":4', $client->getResponse()->getContent());
    }

    public function testJwtInvalid()
    {
        $client = static::createClient([], ['HTTP_AUTHORIZATION' => 'Bearer token']);
        $client->request('GET', '/v1.0/users/1');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":5', $client->getResponse()->getContent());
    }

    public function testJwtExpired()
    {
        $client = static::createClient([], ['HTTP_AUTHORIZATION' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJpYXQiOjE1NjYzODg5NzksImV4cCI6MTU2NjM5MjU3OSwicm9sZXMiOlsiUk9MRV9VU0VSIiwiUk9MRV9BRE1JTiJdLCJ1c2VybmFtZSI6InVzZXJuYW1lIn0.IsvrAL-GdZMcxuO3MiT7lPMSUCVlOLHVVreIMO8ZeY2BMLlPUu-NQA-q7ujEkb6bNMPkmWPM-hxBrkfKuHXhd0ARIx0rX3kr9MZA3QOUpjJEbgHahaHRAR9rZn6bMCORszPnIqmOEtcTz6Ng1N0BfAuEj4XlNTUAe-LvQm_lSWUim3xiCgDtgCyRvSif8SkPD4In6i-0eL_1uQxAezMbEVq6gAosCUde1YayJx3BLIsEUbfOfH-J0wlIssKSw_sPocq5FLAhdKAAs6c_QtBgdPn-sm4Ds-hPVpD-kA7kzB-niA2jL6eR51o9TNWCGc-o1WNahuwMr4_b4FPzY5dFWyNyznwl1-tnf5g1j3ZGTmZU2C9xP9uFlsR1z0lyTtQct5kjqncCZn_OGm9F3F6vTiCDFOqAMTbZer_XoDGwOJ59j3_GXQcGGoMp9zHnOF80_888Dk6ihElPmIW-w3BEGeSoTEu1xS8UjNFFjDr60iuv6CGJef402GGeRPT9ksjTBr_7L_3r30G9PHN3y-rJu0o_cxFLczoKf5InhkP6yx9myeeiDlOrCkLJzryezIf1vl1t8hlK6jQ8Gtmm4kJM_RxvvOLyANi4_D5ntSRyhSZ8a1UbTS3oauQUaJs4ksk_QSxs7WvoaZ-sJ1MHsmXQgcnnkFKrcL-i4M1ZPAUjv2Q']);
        $client->request('GET', '/v1.0/users/1');

        $this->assertEquals(401, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"code":6', $client->getResponse()->getContent());
    }
}
