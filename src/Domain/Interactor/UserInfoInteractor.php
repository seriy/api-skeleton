<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UserInfoInput;
use App\Domain\Output\UserInfoOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;

class UserInfoInteractor implements InteractorInterface
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
    public function execute(UserInfoInput $input, PresenterInterface $presenter): void
    {
        $this->checkUser($input->currentUserId);

        if (null === $user = $this->userRepository->getUser($input->userId)) {
            throw new DomainException(UserError::USER_NOT_FOUND, [$input->userId]);
        }

        if ($user->isBlocked() || $user->isDeleted()) {
            throw new DomainException(UserError::USER_NOT_FOUND, [$input->userId]);
        }

        $presenter->setOutput(new UserInfoOutput($user));
    }
}
