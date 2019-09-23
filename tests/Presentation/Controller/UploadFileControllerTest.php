<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Controller;

use App\Presentation\Test\WebTestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use function getenv;

class UploadFileControllerTest extends WebTestCase
{
    public function testBadRequest()
    {
        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request('POST', '/v1.0/files');

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testNotUploaded()
    {
        $file = new UploadedFile(
            __DIR__.'/../../../public/files/test.gif',
            'original.gif',
            null,
            UPLOAD_ERR_CANT_WRITE,
            true
        );

        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'POST',
            '/v1.0/files',
            [],
            ['file' => [$file]]
        );

        $this->assertEquals(400, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"errors"', $client->getResponse()->getContent());
    }

    public function testSuccess()
    {
        $file = new UploadedFile(
            __DIR__.'/../../../public/files/test.gif',
            'original.gif',
            null,
            UPLOAD_ERR_OK,
            true
        );

        $client = $this->createClientWithJwt(getenv('USERNAME'), getenv('PASSWORD'));
        $client->request(
            'POST',
            '/v1.0/files',
            [],
            ['file' => [$file]]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertJson($client->getResponse()->getContent());
        $this->assertContains('"data"', $client->getResponse()->getContent());
    }
}
