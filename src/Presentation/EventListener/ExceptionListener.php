<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Presentation\Presenter\ExceptionPresenterTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;

class ExceptionListener
{
    use ExceptionPresenterTrait;

    public function onKernelException(ExceptionEvent $event): void
    {
        $event->setResponse(new JsonResponse(
            $this->normalize($event->getException()),
            $this->getStatusCode($event->getException())
        ));
    }
}
