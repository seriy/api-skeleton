<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\RemoveUserInput;
use App\Domain\Output\RemoveUserOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;

class RemoveUserInteractor implements InteractorInterface
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
    public function execute(RemoveUserInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        $user->setDeletedAt(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new RemoveUserOutput($user));
    }
}
