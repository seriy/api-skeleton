<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\AddUserDeviceTokenInput;
use App\Domain\Interactor\AddUserDeviceTokenInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AddUserDeviceTokenInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $input = new AddUserDeviceTokenInput($currentUserId = 1, $userId = 1, $token = 'token');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new AddUserDeviceTokenInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $input = new AddUserDeviceTokenInput($currentUserId = 1, $userId = 2, $token = 'token');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new AddUserDeviceTokenInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new AddUserDeviceTokenInput($currentUserId = 1, $userId = 1, $token = 'token');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('addDeviceToken')
            ->with($token);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new AddUserDeviceTokenInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
