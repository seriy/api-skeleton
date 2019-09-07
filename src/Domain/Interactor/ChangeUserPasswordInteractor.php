<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ChangeUserPasswordInput;
use App\Domain\Output\ChangeUserPasswordOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;

class ChangeUserPasswordInteractor implements InteractorInterface
{
    use UserCheckerTrait;

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
     */
    public function execute(ChangeUserPasswordInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        if (!$this->passwordEncoder->isPasswordValid($user, $input->currentPassword)) {
            throw new DomainException(UserError::PASSWORD_INCORRECT);
        }

        $user->setPassword($this->passwordEncoder->encodePassword($user, $input->newPassword));
        $this->userRepository->saveUser($user);

        $presenter->setOutput(new ChangeUserPasswordOutput($user));
    }
}
