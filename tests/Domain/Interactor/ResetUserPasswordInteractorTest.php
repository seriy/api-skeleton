<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Input\ResetUserPasswordInput;
use App\Domain\Interactor\ResetUserPasswordInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class ResetUserPasswordInteractorTest extends TestCase
{
    public function testEmailNotFound()
    {
        $input = new ResetUserPasswordInput($email = 'username@example.com');

        $this->expectException(DomainException::class);

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn(null);

        $interactor = new ResetUserPasswordInteractor($userRepository);
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new ResetUserPasswordInput($email = 'username@example.com');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('getUserByEmail')
            ->with($email)
            ->willReturn($user = $this->createMock(UserInterface::class));

        $user
            ->expects($this->once())
            ->method('setPasswordResettingToken');
        $user
            ->expects($this->once())
            ->method('setPasswordResettingRequestedAt');

        $userRepository
            ->expects($this->once())
            ->method('saveUser')
            ->with($user);

        $presenter = $this->createMock(PresenterInterface::class);
        $presenter
            ->expects($this->once())
            ->method('setOutput');

        $interactor = new ResetUserPasswordInteractor($userRepository);
        $interactor->execute($input, $presenter);
    }
}
