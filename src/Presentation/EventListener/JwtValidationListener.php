<?php

declare(strict_types=1);

namespace App\Presentation\EventListener;

use App\Presentation\Error\AuthenticationError;
use App\Presentation\Exception\PresentationException;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTExpiredEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTInvalidEvent;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTNotFoundEvent;

class JwtValidationListener
{
    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onAuthenticationFailure(AuthenticationFailureEvent $event): void
    {
        throw new PresentationException(AuthenticationError::BAD_CREDENTIALS);
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onJwtNotFound(JWTNotFoundEvent $event): void
    {
        throw new PresentationException(AuthenticationError::JWT_NOT_FOUND);
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onJwtInvalid(JWTInvalidEvent $event): void
    {
        throw new PresentationException(AuthenticationError::JWT_INVALID);
    }

    /**
     * @throws \App\Presentation\Exception\PresentationException
     */
    public function onJwtExpired(JWTExpiredEvent $event): void
    {
        throw new PresentationException(AuthenticationError::JWT_EXPIRED);
    }
}
