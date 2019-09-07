<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UnblockUserInput;
use App\Domain\Interactor\UnblockUserInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UnblockUserInteractorTest extends TestCase
{
    public function testCurrentUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UnblockUserInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new UnblockUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $this->expectException(DomainException::class);

        $input = new UnblockUserInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isAdmin')
            ->willReturn(false);

        $interactor = new UnblockUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSelfBlocking()
    {
        $this->expectException(DomainException::class);

        $input = new UnblockUserInput($currentUserId = 1, $userId = 1);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($currentUser = $this->createMock(UserInterface::class));

        $currentUser
            ->expects($this->once())
            ->method('isAdmin')
            ->willReturn(true);

        $interactor = new UnblockUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UnblockUserInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->exactly(2))
            ->method('getUser')
            ->willReturnMap([
                [$currentUserId, $currentUser = $this->createMock(UserInterface::class)],
                [$userId, null],
            ]);

        $currentUser
            ->expects($this->once())
            ->method('isAdmin')
            ->willReturn(true);

        $interactor = new UnblockUserInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new UnblockUserInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->exactly(2))
            ->method('getUser')
            ->willReturnMap([
                [$currentUserId, $currentUser = $this->createMock(UserInterface::class)],
                [$userId, $user = $this->createMock(UserInterface::class)],
            ]);

        $currentUser
            ->expects($this->once())
            ->method('isAdmin')
            ->willReturn(true);

        $user
            ->expects($this->once())
            ->method('setBlockedTo')
            ->with(null);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new UnblockUserInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
