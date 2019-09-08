<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\SetUserPasswordInput;
use App\Domain\Output\SetUserPasswordOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;
use DateInterval;
use DateTimeImmutable;

class SetUserPasswordInteractor implements InteractorInterface
{
    private $userRepository;
    private $passwordEncoder;

    public function __construct(
        UserRepositoryInterface $userRepository,
        PasswordEncoderInterface $passwordEncoder
    ) {
        $this->userRepository = $userRepository;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function execute(SetUserPasswordInput $input, PresenterInterface $presenter): void
    {
        if (null === $user = $this->userRepository->getUserByPasswordResettingToken($input->resettingToken)) {
            throw new DomainException(UserError::PASSWORD_RESETTING_TOKEN_INCORRECT, [$input->resettingToken]);
        }

        if (null === $requestedAt = $user->getPasswordResettingRequestedAt()) {
            throw new DomainException(UserError::PASSWORD_RESETTING_TOKEN_EXPIRED, [$input->resettingToken]);
        }

        $validTo = $requestedAt->add(new DateInterval(sprintf('PT%dS', UserInterface::SECONDS_BEFORE_TOKEN_EXPIRATION)));

        if ($validTo->getTimestamp() < (new DateTimeImmutable())->getTimestamp()) {
            throw new DomainException(UserError::PASSWORD_RESETTING_TOKEN_EXPIRED, [$input->resettingToken]);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $input->newPassword));
        $user->setPasswordResettingToken(null);
        $user->setPasswordResettingRequestedAt(null);
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new SetUserPasswordOutput($user));
    }
}
