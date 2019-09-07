<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\UserListInput;
use App\Domain\Interactor\UserListInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class UserListInteractorTest extends TestCase
{
    public function testCurrentUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new UserListInput(
            $currentUserId = 1,
            $filters = [],
            $sorts = [],
            $limit = 10,
            $offset = 0
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new UserListInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testCurrentUserBlocked()
    {
        $this->expectException(DomainException::class);

        $input = new UserListInput(
            $currentUserId = 1,
            $filters = [],
            $sorts = [],
            $limit = 10,
            $offset = 0
        );

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

        $interactor = new UserListInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testCurrentUserDeleted()
    {
        $this->expectException(DomainException::class);

        $input = new UserListInput(
            $currentUserId = 1,
            $filters = [],
            $sorts = [],
            $limit = 10,
            $offset = 0
        );

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

        $interactor = new UserListInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new UserListInput(
            $currentUserId = 1,
            $filters = [],
            $sorts = [],
            $limit = 10,
            $offset = 0
        );

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
            ->willReturn(false);

        $userRepository
            ->expects($this->once())
            ->method('getTotalUsers')
            ->with($filters)
            ->willReturn(1);
        $userRepository
            ->expects($this->once())
            ->method('getUsers')
            ->with($filters, $sorts, $limit, $offset)
            ->willReturn([$this->createMock(UserInterface::class)]);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new UserListInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
