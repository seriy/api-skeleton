<?php

declare(strict_types=1);

namespace App\Tests\Domain\Interactor;

use App\Domain\Entity\UserInterface;
use App\Domain\Exception\DomainException;
use App\Domain\Factory\UserFactory;
use App\Domain\Input\AddUserInput;
use App\Domain\Interactor\AddUserInteractor;
use App\Domain\Presenter\PresenterInterface;
use App\Domain\Repository\UserRepositoryInterface;
use PHPUnit\Framework\TestCase;

class AddUserInteractorTest extends TestCase
{
    public function testEmailTaken()
    {
        $this->expectException(DomainException::class);

        $input = new AddUserInput(
            $email = 'username@example.com',
            $username = 'username',
            $password = 'password',
            $firstName = 'first',
            $lastName = 'last'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email)
            ->willReturn(true);

        $interactor = new AddUserInteractor($userRepository, $this->createMock(UserFactory::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testUsernameTaken()
    {
        $this->expectException(DomainException::class);

        $input = new AddUserInput(
            $email = 'username@example.com',
            $username = 'username',
            $password = 'password',
            $firstName = 'first',
            $lastName = 'last'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email)
            ->willReturn(false);
        $userRepository
            ->expects($this->once())
            ->method('isUsernameTaken')
            ->with($username)
            ->willReturn(true);

        $interactor = new AddUserInteractor($userRepository, $this->createMock(UserFactory::class));
        $interactor->execute($input, $this->createMock(PresenterInterface::class));
    }

    public function testSuccess()
    {
        $input = new AddUserInput(
            $email = 'username@example.com',
            $username = 'username',
            $password = 'password',
            $firstName = 'first',
            $lastName = 'last'
        );

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->once())
            ->method('isEmailTaken')
            ->with($email)
            ->willReturn(false);
        $userRepository
            ->expects($this->once())
            ->method('isUsernameTaken')
            ->with($username)
            ->willReturn(false);

        $userFactory = $this->createMock(UserFactory::class);
        $userFactory
            ->expects($this->once())
            ->method('create')
            ->with($email, $password, $username, $firstName, $lastName)
            ->willReturn($user = $this->createMock(UserInterface::class));

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

        $interactor = new AddUserInteractor($userRepository, $userFactory);
        $interactor->execute($input, $presenter);
    }
}
