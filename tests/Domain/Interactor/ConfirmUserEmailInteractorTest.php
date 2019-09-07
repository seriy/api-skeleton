<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ConfirmUserEmailInput;
use App\Domain\Interactor\ConfirmUserEmailInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class ConfirmUserEmailInteractorTest extends TestCase
{
    public function testTokenIncorrect()
    {
        $this->expectException(DomainException::class);

        $input = new ConfirmUserEmailInput($confirmationToken = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmailConfirmationToken')
            ->with($confirmationToken)
            ->willReturn(null);

        $interactor = new ConfirmUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testEmailConfirmed()
    {
        $this->expectException(DomainException::class);

        $input = new ConfirmUserEmailInput($confirmationToken = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmailConfirmationToken')
            ->with($confirmationToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(true);

        $interactor = new ConfirmUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testNotRequested()
    {
        $this->expectException(DomainException::class);

        $input = new ConfirmUserEmailInput($confirmationToken = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmailConfirmationToken')
            ->with($confirmationToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(false);
        $user
            ->expects($this->once())
            ->method('getEmailConfirmationRequestedAt')
            ->willReturn(null);

        $interactor = new ConfirmUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testTokenExpired()
    {
        $this->expectException(DomainException::class);

        $input = new ConfirmUserEmailInput($confirmationToken = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmailConfirmationToken')
            ->with($confirmationToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(false);
        $user
            ->expects($this->once())
            ->method('getEmailConfirmationRequestedAt')
            ->willReturn($requestedAt = $this->createMock(DateTimeImmutable::class));

        $requestedAt
            ->expects($this->once())
            ->method('add')
            ->willReturn($validTo = $this->createMock(DateTimeImmutable::class));

        $validTo
            ->expects($this->once())
            ->method('getTimestamp');

        $interactor = new ConfirmUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new ConfirmUserEmailInput($confirmationToken = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmailConfirmationToken')
            ->with($confirmationToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(false);
        $user
            ->expects($this->once())
            ->method('getEmailConfirmationRequestedAt')
            ->willReturn($requestedAt = $this->createMock(DateTimeImmutable::class));

        $requestedAt
            ->expects($this->once())
            ->method('add')
            ->willReturn($validTo = $this->createMock(DateTimeImmutable::class));

        $validTo
            ->expects($this->once())
            ->method('getTimestamp')
            ->willReturn(4294967295); // max unsigned int32

        $user
            ->expects($this->once())
            ->method('setEmailConfirmed')
            ->with(true);
        $user
            ->expects($this->once())
            ->method('setEmailConfirmationToken')
            ->with(null);
        $user
            ->expects($this->once())
            ->method('setEmailConfirmationRequestedAt')
            ->with(null);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new ConfirmUserEmailInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
