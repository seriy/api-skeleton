<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\VerifyUserEmailInput;
use App\Domain\Interactor\VerifyUserEmailInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class VerifyUserEmailInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $this->expectException(DomainException::class);

        $input = new VerifyUserEmailInput($currentUserId = 1, $userId = 1);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new VerifyUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $this->expectException(DomainException::class);

        $input = new VerifyUserEmailInput($currentUserId = 1, $userId = 2);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new VerifyUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testEmailConfirmed()
    {
        $this->expectException(DomainException::class);

        $input = new VerifyUserEmailInput($currentUserId = 1, $userId = 1);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(true);

        $interactor = new VerifyUserEmailInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new VerifyUserEmailInput($currentUserId = 1, $userId = 1);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('isEmailConfirmed')
            ->willReturn(false);

        $user
            ->expects($this->once())
            ->method('setEmailConfirmationToken');
        $user
            ->expects($this->once())
            ->method('setEmailConfirmationRequestedAt');

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new VerifyUserEmailInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
