<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Domain\Exception\ExceptionInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class ExceptionListener
{
    private const MIN_HTTP_STATUS_CODE = 100;
    private const MAX_HTTP_STATUS_CODE = 599;

    public function onKernelException(ExceptionEvent $event): void
    {
        $event->setResponse(new JsonResponse(
            $this->getData($event->getException()),
            $this->getStatusCode($event->getException())
        ));
    }

    private function getData(Throwable $throwable): array
    {
        $error = [
            'detail' => $throwable->getMessage(),
            'status' => $this->getStatusCode($throwable),
        ];

        if ($throwable instanceof ExceptionInterface) {
            $error['code'] = $throwable->getCode();
        }

        return ['errors' => [$error]];
    }

    private function getStatusCode(Throwable $throwable): int
    {
        $code = ($throwable->getCode() < self::MIN_HTTP_STATUS_CODE || $throwable->getCode() > self::MAX_HTTP_STATUS_CODE)
            ? Response::HTTP_INTERNAL_SERVER_ERROR
            : $throwable->getCode();

        if ($throwable instanceof HttpExceptionInterface || $throwable instanceof ExceptionInterface) {
            $code = $throwable->getStatusCode();
        }

        return $code;
    }
}
