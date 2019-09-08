<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\RecoverUserInput;
use App\Domain\Output\RecoverUserOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;

class RecoverUserInteractor implements InteractorInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     */
    public function execute(RecoverUserInput $input, PresenterInterface $presenter): void
    {
        if (null === $user = $this->userRepository->getUser($input->currentUserId)) {
            throw new DomainException(UserError::USER_NOT_FOUND, [$input->currentUserId]);
        }

        if ($user->isBlocked()) {
            throw new DomainException(UserError::USER_BLOCKED, [$input->currentUserId]);
        }

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        if (!$user->isDeleted()) {
            throw new DomainException(UserError::USER_ACTIVE, [$input->currentUserId]);
        }

        $user->setDeletedAt(null);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new RecoverUserOutput($user));
    }
}
