<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;

trait UserCheckerTrait
{
    /**
     * @throws \App\Domain\Exception\DomainException
     */
    private function checkUser(?int $userId): ?UserInterface
    {
        if (null === $userId) {
            return null;
        }

        /** @var \App\Domain\Entity\UserInterface $user */
        if (null === $user = $this->userRepository->getUser($userId)) {
            throw new DomainException(UserError::USER_NOT_FOUND, [$userId]);
        }

        if ($user->isBlocked()) {
            throw new DomainException(UserError::USER_BLOCKED, [$userId]);
        }

        if ($user->isDeleted()) {
            throw new DomainException(UserError::USER_DELETED, [$userId]);
        }

        return $user;
    }
}
