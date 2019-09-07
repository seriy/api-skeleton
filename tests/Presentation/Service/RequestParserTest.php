<?php

declare(strict_types=1);

namespace App\Tests\Presentation\Service;

use App\Presentation\Service\RequestParser;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use function json_encode;

class RequestParserTest extends TestCase
{
    private $parser;

    protected function setUp()
    {
        $query = $this->createMock(ParameterBag::class);
        $query
            ->expects($this->once())
            ->method('all')
            ->willReturn([
                'fields' => [
                    'film' => 'name,date',
                ],
                'include' => 'actor,director',
                'filter' => [
                    'name' => 'false,true',
                ],
                'sort' => '-id,name',
                'page' => [
                    'limit' => 1,
                    'offset' => 0,
                ],
            ]);

        $attributes = $this->createMock(ParameterBag::class);
        $attributes
            ->expects($this->once())
            ->method('all')
            ->willReturn([
                '_route_params' => [
                    'id' => 1,
                ],
            ]);

        $request = $this->createMock(Request::class);
        $request
            ->expects($this->once())
            ->method('getContent')
            ->willReturn(json_encode([
                'data' => [
                    'float' => 3.14,
                    'boolean' => true,
                    'bool' => 'false',
                    'date' => '2019-12-12',
                ],
            ]));

        $ref = new ReflectionProperty(Request::class, 'query');
        $ref->setValue($request, $query);

        $ref = new ReflectionProperty(Request::class, 'attributes');
        $ref->setValue($request, $attributes);

        $requestStack = $this->createMock(RequestStack::class);
        $requestStack
            ->expects($this->once())
            ->method('getCurrentRequest')
            ->willReturn($request);

        $this->parser = new RequestParser($requestStack);
    }

    public function testFields()
    {
        $this->assertEquals(['film' => ['name', 'date']], $this->parser->getFields());
    }

    public function testIncludes()
    {
        $this->assertEquals(['actor', 'director'], $this->parser->getIncludes());
    }

    public function testFilters()
    {
        $this->assertEquals(['name' => ['false', 'true']], $this->parser->getFilters());
    }

    public function testSorts()
    {
        $this->assertEquals(['-id', 'name'], $this->parser->getSorts());
    }

    public function testLimit()
    {
        $this->assertEquals(1, $this->parser->getLimit());
    }

    public function testOffset()
    {
        $this->assertEquals(0, $this->parser->getOffset());
    }

    public function testDate()
    {
        $this->assertNull($this->parser->getDate('id'));
        $this->assertInstanceOf(DateTimeImmutable::class, $this->parser->getDate('data.date'));
    }

    public function testArray()
    {
        $this->assertEquals(
            [
                'fields' => [
                    'film' => ['name', 'date'],
                ],
                'include' => ['actor', 'director'],
                'filter' => [
                    'name' => ['false', 'true'],
                ],
                'sort' => ['-id', 'name'],
                'page' => [
                    'limit' => 1,
                    'offset' => 0,
                ],
                'data' => [
                    'float' => 3.14,
                    'boolean' => true,
                    'bool' => 'false',
                    'date' => '2019-12-12',
                ],
                'id' => 1,
            ],
            $this->parser->getArray()
        );
        $this->assertEquals(
            [
                'float' => 3.14,
                'boolean' => true,
                'bool' => 'false',
                'date' => '2019-12-12',
            ],
            $this->parser->getArray('data')
        );
        $this->assertEquals([], $this->parser->getArray('missed.data'));
    }

    public function testBoolean()
    {
        $this->assertTrue($this->parser->getBoolean('id'));
        $this->assertFalse($this->parser->getBoolean('data.bool'));
        $this->assertTrue($this->parser->getBoolean('data.boolean'));
    }

    public function testInt()
    {
        $this->assertEquals(1, $this->parser->getInt('id'));
    }

    public function testFloat()
    {
        $this->assertEquals(3.14, $this->parser->getFloat('data.float'));
    }

    public function testString()
    {
        $this->assertEquals('false', $this->parser->getString('data.bool'));
    }
}
