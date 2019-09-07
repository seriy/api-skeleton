<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ResetUserPasswordInput;
use App\Domain\Output\ResetUserPasswordOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\TokenGenerator;
use DateTimeImmutable;

class ResetUserPasswordInteractor implements InteractorInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function execute(ResetUserPasswordInput $input, PresenterInterface $presenter): void
    {
        if (null === $user = $this->userRepository->getUserByEmail($input->email)) {
            throw new DomainException(UserError::EMAIL_NOT_FOUND, [$input->email]);
        }

        $user->setPasswordResettingToken((new TokenGenerator())->generateToken());
        $user->setPasswordResettingRequestedAt(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        // todo: send email with resetting token

        $presenter->setOutput(new ResetUserPasswordOutput($user));
    }
}
