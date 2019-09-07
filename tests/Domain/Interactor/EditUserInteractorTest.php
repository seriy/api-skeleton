<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\EditUserInput;
use App\Domain\Interactor\EditUserInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class EditUserInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 1,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $this->expectException(DomainException::class);

        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 2,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testEmailTaken()
    {
        $this->expectException(DomainException::class);

        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 1,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email, $currentUserId)
            ->willReturn(true);

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUsernameTaken()
    {
        $this->expectException(DomainException::class);

        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 1,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email, $currentUserId)
            ->willReturn(false);
        $userRepository
            ->expects($this->once())
            ->method('isUsernameTaken')
            ->with($username, $currentUserId)
            ->willReturn(true);

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccessWithTheSameEmail()
    {
        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 1,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email, $currentUserId)
            ->willReturn(false);
        $userRepository
            ->expects($this->once())
            ->method('isUsernameTaken')
            ->with($username, $currentUserId)
            ->willReturn(false);

        $user
            ->expects($this->once())
            ->method('getEmail')
            ->willReturn($email);
        $user
            ->expects($this->once())
            ->method('setUsername')
            ->with($username);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }

    public function testSuccessWithDifferentEmail()
    {
        $input = new EditUserInput(
            $currentUserId = 1,
            $userId = 1,
            $email = 'username@example.com',
            $username = 'username'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email, $currentUserId)
            ->willReturn(false);
        $userRepository
            ->expects($this->once())
            ->method('isUsernameTaken')
            ->with($username, $currentUserId)
            ->willReturn(false);

        $user
            ->expects($this->once())
            ->method('getEmail')
            ->willReturn($confirmedEmail = 'confirmed@example.com');
        $user
            ->expects($this->once())
            ->method('setEmailConfirmed')
            ->with(false);
        $user
            ->expects($this->once())
            ->method('setEmailConfirmationToken');
        $user
            ->expects($this->once())
            ->method('setEmailConfirmationRequestedAt');
        $user
            ->expects($this->once())
            ->method('setUsername')
            ->with($username);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new EditUserInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
