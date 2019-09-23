<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\AddOAuthProviderInput;
use App\Domain\Interactor\AddOAuthProviderInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AddOAuthProviderInteractorTest extends TestCase
{
    public function testUserNotFound()
    {
        $input = new AddOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'google', $providerId = '100');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn(null);

        $interactor = new AddOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testPermissionDenied()
    {
        $input = new AddOAuthProviderInput($currentUserId = 1, $userId = 2, $provider = 'google', $providerId = '100');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new AddOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUnsupportedProvider()
    {
        $input = new AddOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'twitter', $providerId = '100');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $interactor = new AddOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new AddOAuthProviderInput($currentUserId = 1, $userId = 1, $provider = 'google', $providerId = '100');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUser')
            ->with($currentUserId)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('setGoogleId')
            ->with($providerId);

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new AddOAuthProviderInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
