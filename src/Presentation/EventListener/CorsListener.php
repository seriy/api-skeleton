<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use function getenv;

class CorsListener
{
    private $origin;

    public function __construct()
    {
        $this->origin = getenv('CORS_ORIGIN') ?: '*';
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if ($this->isPreflightRequest($event->getRequest())) {
            $event->setResponse(new JsonResponse(null, Response::HTTP_NO_CONTENT));
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        $event->getResponse()->headers->add([
            'Access-Control-Allow-Origin' => $this->origin,
            'Access-Control-Allow-Methods' => 'GET, POST, PATCH, DELETE, OPTIONS',
            'Access-Control-Allow-Headers' => 'Content-Type, Authorization, X-Api-Key',
            'Access-Control-Allow-Credentials' => 'true',
        ]);
    }

    private function isPreflightRequest(Request $request): bool
    {
        return $this->isCorsRequest($request) && $request->isMethod(Request::METHOD_OPTIONS);
    }

    private function isCorsRequest(Request $request): bool
    {
        return $this->origin && !$this->isSameHost($request);
    }

    private function isSameHost(Request $request): bool
    {
        return $this->origin === $request->getSchemeAndHttpHost();
    }
}
