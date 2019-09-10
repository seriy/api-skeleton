<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Exception\ExceptionInterface;
use JsonApiPhp\JsonApi\Error;
use JsonApiPhp\JsonApi\ErrorDocument;
use JsonApiPhp\JsonApi\JsonApi;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

trait ExceptionPresenterTrait
{
    private static $MIN_HTTP_STATUS_CODE = 100;
    private static $MAX_HTTP_STATUS_CODE = 599;

    public function normalize(Throwable $throwable): ErrorDocument
    {
        $members = [
            new Error\Detail($throwable->getMessage()),
            new Error\Status((string) $this->getStatusCode($throwable)),
        ];

        if ($throwable instanceof ExceptionInterface) {
            $members[] = new Error\Code((string) $throwable->getCode());
        }

        return new ErrorDocument(new Error(...$members), new JsonApi());
    }

    private function getStatusCode(Throwable $throwable): int
    {
        $code = ($throwable->getCode() < self::$MIN_HTTP_STATUS_CODE || $throwable->getCode() > self::$MAX_HTTP_STATUS_CODE)
            ? Response::HTTP_INTERNAL_SERVER_ERROR
            : $throwable->getCode();

        if ($throwable instanceof HttpExceptionInterface || $throwable instanceof ExceptionInterface) {
            $code = $throwable->getStatusCode();
        }

        return $code;
    }
}
