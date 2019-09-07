<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Presentation\Error\AuthenticationError;
use App\Presentation\Exception\PresentationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use function getenv;
use function preg_match;

class KeyValidationListener
{
    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();

        if (!$this->isSupported($request->getPathInfo())) {
            return;
        }

        if (null === $key = $this->getKey($request)) {
            throw new PresentationException(AuthenticationError::KEY_NOT_FOUND);
        }

        if (!$this->isValid($key)) {
            throw new PresentationException(AuthenticationError::KEY_INVALID);
        }
    }

    private function isSupported(string $path): bool
    {
        return (bool) preg_match('/^\/v(\d+\.\d+)\/(confirm|login|refresh|register|reset|set)/', $path);
    }

    private function getKey(Request $request): ?string
    {
        return $request->headers->get('X-Api-Key', null, true);
    }

    private function isValid(string $key): bool
    {
        return sha1(sha1(date('Y-m-d H')).getenv('KEY_SALT')) === $key;
    }
}
