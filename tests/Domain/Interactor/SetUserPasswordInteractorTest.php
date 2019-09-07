<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\SetUserPasswordInput;
use App\Domain\Interactor\SetUserPasswordInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class SetUserPasswordInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new SetUserPasswordInput($resettingToken = 'token', $newPassword = 'password');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByPasswordResettingToken')
            ->with($resettingToken)
            ->willReturn(null);

        $interactor = new SetUserPasswordInteractor($userRepository, $this->createMock(PasswordEncoderInterface::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testNotRequested()
    {
        $this->expectException(DomainException::class);

        $input = new SetUserPasswordInput($resettingToken = 'token', $newPassword = 'password');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByPasswordResettingToken')
            ->with($resettingToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('getPasswordResettingRequestedAt')
            ->willReturn(null);

        $interactor = new SetUserPasswordInteractor($userRepository, $this->createMock(PasswordEncoderInterface::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testTokenExpired()
    {
        $this->expectException(DomainException::class);

        $input = new SetUserPasswordInput($resettingToken = 'token', $newPassword = 'password');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByPasswordResettingToken')
            ->with($resettingToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('getPasswordResettingRequestedAt')
            ->willReturn($requestedAt = $this->createMock(DateTimeImmutable::class));

        $requestedAt
            ->expects($this->once())
            ->method('add')
            ->willReturn($validTo = $this->createMock(DateTimeImmutable::class));

        $validTo
            ->expects($this->once())
            ->method('getTimestamp');

        $interactor = new SetUserPasswordInteractor($userRepository, $this->createMock(PasswordEncoderInterface::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new SetUserPasswordInput($resettingToken = 'token', $newPassword = 'password');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByPasswordResettingToken')
            ->with($resettingToken)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('getPasswordResettingRequestedAt')
            ->willReturn($requestedAt = $this->createMock(DateTimeImmutable::class));

        $requestedAt
            ->expects($this->once())
            ->method('add')
            ->willReturn($validTo = $this->createMock(DateTimeImmutable::class));

        $validTo
            ->expects($this->once())
            ->method('getTimestamp')
            ->willReturn(4294967295);

        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, $newPassword)
            ->willReturn($password = 'generated password');

        $user
            ->expects($this->once())
            ->method('setPassword')
            ->with($password);
        $user
            ->expects($this->once())
            ->method('setPasswordResettingToken')
            ->with(null);
        $user
            ->expects($this->once())
            ->method('setPasswordResettingRequestedAt')
            ->with(null);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new SetUserPasswordInteractor($userRepository, $passwordEncoder);
        $interactor->execute($input, $presenter);
    }
}
