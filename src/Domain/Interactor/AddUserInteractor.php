<?php

declare(strict_types=1);

namespace App\Domain\Interactor;

use App\Domain\Error\UserError;
use App\Domain\Exception\DomainException;
use App\Domain\Factory\UserFactory;
use App\Domain\Input\AddUserInput;
use App\Domain\Output\AddUserOutput;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\TokenGenerator;
use DateTimeImmutable;

class AddUserInteractor implements InteractorInterface
{
    private $userRepository;
    private $userFactory;

    public function __construct(
        UserRepositoryInterface $userRepository,
        UserFactory $userFactory
    ) {
        $this->userRepository = $userRepository;
        $this->userFactory = $userFactory;
    }

    /**
     * @throws \App\Domain\Exception\DomainException
     * @throws \Exception
     */
    public function execute(AddUserInput $input, PresenterInterface $presenter): void
    {
        if ($this->userRepository->isEmailTaken($input->email)) {
            throw new DomainException(UserError::EMAIL_TAKEN, [$input->email]);
        }

        if ($this->userRepository->isUsernameTaken($input->username)) {
            throw new DomainException(UserError::USERNAME_TAKEN, [$input->username]);
        }

        $user = $this->userFactory->create(
            $input->email,
            $input->password,
            $input->username,
            $input->firstName,
            $input->lastName
        );
        $user->setEmailConfirmationToken((new TokenGenerator())->generateToken());
        $user->setEmailConfirmationRequestedAt(new DateTimeImmutable());
        $this->userRepository->saveUser($user);

        // todo: send email with confirmation token

        $presenter->setOutput(new AddUserOutput($user));
    }
}
