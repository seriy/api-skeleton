<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UnblockUserInput;
use App\Domain\Output\UnblockUserOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UnblockUserInteractor implements InteractorInterface
{
    use UserCheckerTrait;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     */
    public function execute(UnblockUserInput $input, PresenterInterface $presenter): void
    {
        $currentUser = $this->checkUser($input->currentUserId);

        if (!$currentUser->isAdmin()) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        if ($input->currentUserId === $input->userId) {
            throw new DomainException(UserError::CANNOT_UNBLOCK_YOURSELF);
        }

        if (null === $user = $this->userRepository->getUser($input->userId)) {
            throw new DomainException(UserError::USER_NOT_FOUND, [$input->userId]);
        }

        $user->setBlockedTo(null);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new UnblockUserOutput($user));
    }
}
