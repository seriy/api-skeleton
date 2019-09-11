<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ChangeUserPhotoInput;
use App\Domain\Interactor\ChangeUserPhotoInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ChangeUserPhotoInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new ChangeUserPhotoInput(
            $currentUserId = 1,
            $userId = 1,
            $photo = 'files/1.jpg',
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new ChangeUserPhotoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $this->expectException(DomainException::class);

        $input = new ChangeUserPhotoInput(
            $currentUserId = 1,
            $userId = 2,
            $photo = 'files/1.jpg'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new ChangeUserPhotoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new ChangeUserPhotoInput(
            $currentUserId = 1,
            $userId = 1,
            $photo = 'files/1.jpg'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('setPhoto')
            ->with($photo);

        $interactor = new ChangeUserPhotoInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }
}
