<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Presentation\Error\AuthenticationError;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

class JwtValidationListener
{
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        $event->setResponse(new JsonResponse(['errors' => [[
            'detail' => AuthenticationError::BAD_CREDENTIALS['message'],
            'status' => AuthenticationError::BAD_CREDENTIALS['status'],
            'code' => AuthenticationError::BAD_CREDENTIALS['code'],
        ]]], AuthenticationError::BAD_CREDENTIALS['status']));
    }

    public function onJwtNotFound(JWTNotFoundEvent $event): void
    {
        $event->setResponse(new JsonResponse(['errors' => [[
            'detail' => AuthenticationError::JWT_NOT_FOUND['message'],
            'status' => AuthenticationError::JWT_NOT_FOUND['status'],
            'code' => AuthenticationError::JWT_NOT_FOUND['code'],
        ]]], AuthenticationError::JWT_NOT_FOUND['status']));
    }

    public function onJwtInvalid(JWTInvalidEvent $event): void
    {
        $event->setResponse(new JsonResponse(['errors' => [[
            'detail' => AuthenticationError::JWT_INVALID['message'],
            'status' => AuthenticationError::JWT_INVALID['status'],
            'code' => AuthenticationError::JWT_INVALID['code'],
        ]]], AuthenticationError::JWT_INVALID['status']));
    }

    public function onJwtExpired(JWTExpiredEvent $event): void
    {
        $event->setResponse(new JsonResponse(['errors' => [[
            'detail' => AuthenticationError::JWT_EXPIRED['message'],
            'status' => AuthenticationError::JWT_EXPIRED['status'],
            'code' => AuthenticationError::JWT_EXPIRED['code'],
        ]]], AuthenticationError::JWT_EXPIRED['status']));
    }
}
