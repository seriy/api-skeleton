<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\RemoveOAuthProviderInput;
use App\Domain\Interactor\RemoveOAuthProviderInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use BadMethodCallException;
use PHPUnit\Framework\TestCase;

class RemoveOAuthProviderInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $input = new RemoveOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'google');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new RemoveOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $input = new RemoveOAuthProviderInput($currentUserId = 1, $userId = 2, $provider = 'google');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new RemoveOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUnsupportedProvider()
    {
        $input = new RemoveOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'twitter');

        $this->expectException(BadMethodCallException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new RemoveOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new RemoveOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'google');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('setGoogleId')
            ->with(null);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new RemoveOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
