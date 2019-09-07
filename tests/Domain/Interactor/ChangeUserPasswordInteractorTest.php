<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ChangeUserPasswordInput;
use App\Domain\Interactor\ChangeUserPasswordInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;
use PHPUnit\Framework\TestCase;

class ChangeUserPasswordInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new ChangeUserPasswordInput(
            $currentUserId = 1,
            $userId = 1,
            $currentPassword = 'current',
            $newPassword = 'new'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new ChangeUserPasswordInteractor($userRepository, $this->createMock(PasswordEncoderInterface::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $this->expectException(DomainException::class);

        $input = new ChangeUserPasswordInput(
            $currentUserId = 1,
            $userId = 2,
            $currentPassword = 'current',
            $newPassword = 'new'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new ChangeUserPasswordInteractor($userRepository, $this->createMock(PasswordEncoderInterface::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPasswordIncorrect()
    {
        $this->expectException(DomainException::class);

        $input = new ChangeUserPasswordInput(
            $currentUserId = 1,
            $userId = 1,
            $currentPassword = 'current',
            $newPassword = 'new'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($user, $currentPassword)
            ->willReturn(false);

        $interactor = new ChangeUserPasswordInteractor($userRepository, $passwordEncoder);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new ChangeUserPasswordInput(
            $currentUserId = 1,
            $userId = 1,
            $currentPassword = 'current',
            $newPassword = 'new'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder
            ->expects($this->once())
            ->method('isPasswordValid')
            ->with($user, $currentPassword)
            ->willReturn(true);
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->with($user, $newPassword)
            ->willReturn($password = 'generated password');

        $user
            ->expects($this->once())
            ->method('setPassword')
            ->with($password);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new ChangeUserPasswordInteractor($userRepository, $passwordEncoder);
        $interactor->execute($input, $presenter);
    }
}
