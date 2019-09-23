<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Input\EditUserInput;
use App\Domain\Output\EditUserOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\TokenGenerator;
use DateTimeImmutable;

class EditUserInteractor implements InteractorInterface
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
    public function execute(EditUserInput $input, PresenterInterface $presenter): void
    {
        $user = $this->checkUser($input->currentUserId);

        if ($input->currentUserId !== $input->userId) {
            throw new DomainException(UserError::PERMISSION_DENIED);
        }

        if ($this->userRepository->isEmailTaken($input->email, $input->currentUserId)) {
            throw new DomainException(UserError::EMAIL_TAKEN, [$input->email]);
        }

        if ($this->userRepository->isUsernameTaken($input->username, $input->currentUserId)) {
            throw new DomainException(UserError::USERNAME_TAKEN, [$input->username]);
        }

        if ($user->getEmail() !== $input->email) {
            $user->setEmail($input->email);
            $user->setEmailConfirmed(false);
            $user->setEmailConfirmationToken((new TokenGenerator())->generateToken());
            $user->setEmailConfirmationRequestedAt(new DateTimeImmutable());
        }

        $user->setUsername($input->username);
        $user->setFirstName($input->firstName);
        $user->setLastName($input->lastName);
        $this->userRepository->saveUser($user);

        // todo: send email with confirmation token

        $presenter->setOutput(new EditUserOutput($user));
    }
}
