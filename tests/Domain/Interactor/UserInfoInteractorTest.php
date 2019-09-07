<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UserInfoInput;
use App\Domain\Interactor\UserInfoInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UserInfoInteractorTest extends TestCase
{
    public function testCurrentUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testCurrentUserBlocked()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(true);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testCurrentUserDeleted()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $currentUser
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(true);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->at(0))
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $currentUser
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(false);

        $userRepository
            ->expects($this->at(1))
            ->method('getUser')
            ->with($userId)
            ->willReturn(null);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUserBlocked()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->at(0))
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $currentUser
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(false);

        $userRepository
            ->expects($this->at(1))
            ->method('getUser')
            ->with($userId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(true);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUserDeleted()
    {
        $this->expectException(DomainException::class);

        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->at(0))
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $currentUser
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(false);

        $userRepository
            ->expects($this->at(1))
            ->method('getUser')
            ->with($userId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $user
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(true);

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new UserInfoInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->at(0))
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isBlocked')
            ->willReturn(false);
        $currentUser
            ->expects($this->once())
            ->method('isDeleted')
            ->willReturn(false);

        $userRepository
            ->expects($this->at(1))
            ->method('getUser')
            ->with($userId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new UserInfoInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
