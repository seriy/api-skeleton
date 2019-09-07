<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\VerifyUserEmailInput;
use App\Domain\Output\VerifyUserEmailOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\TokenGenerator;
use DateTimeImmutable;

class VerifyUserEmailInteractor implements InteractorInterface
{
    use UserCheckerTrait;

    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function execute(VerifyUserEmailInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        if ($user->isEmailConfirmed()) {
            throw new DomainException(UserError::EMAIL_CONFIRMED, [$user->getEmail()]);
        }

        $user->setEmailConfirmationToken((new TokenGenerator())->generateToken());
        $user->setEmailConfirmationRequestedAt(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        // todo: send email with confirmation token

        $presenter->setOutput(new VerifyUserEmailOutput($user));
    }
}
