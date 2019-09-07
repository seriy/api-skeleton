<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ConfirmUserEmailInput;
use App\Domain\Output\ConfirmUserEmailOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use DateInterval;
use DateTimeImmutable;
use function sprintf;

class ConfirmUserEmailInteractor implements InteractorInterface
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
    public function execute(ConfirmUserEmailInput $input, PresenterInterface $presenter): void
    {
        if (null === $user = $this->userRepository->getUserByEmailConfirmationToken($input->confirmationToken)) {
            throw new DomainException(UserError::EMAIL_CONFIRMATION_TOKEN_INCORRECT, [$input->confirmationToken]);
        }

        if ($user->isEmailConfirmed()) {
            throw new DomainException(UserError::EMAIL_CONFIRMED, [$user->getEmail()]);
        }

        if (null === $requestedAt = $user->getEmailConfirmationRequestedAt()) {
            throw new DomainException(UserError::EMAIL_CONFIRMATION_TOKEN_EXPIRED, [$input->confirmationToken]);
        }

        $validTo = $requestedAt->add(new DateInterval(sprintf('PT%dS', UserInterface::SECONDS_BEFORE_TOKEN_EXPIRATION)));

        if ($validTo->getTimestamp() < (new DateTimeImmutable())->getTimestamp()) {
            throw new DomainException(UserError::EMAIL_CONFIRMATION_TOKEN_EXPIRED, [$input->confirmationToken]);
        }

        $user->setEmailConfirmed(true);
        $user->setEmailConfirmationToken(null);
        $user->setEmailConfirmationRequestedAt(null);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new ConfirmUserEmailOutput($user));
    }
}
