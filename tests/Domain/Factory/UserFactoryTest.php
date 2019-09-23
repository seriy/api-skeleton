<?php

declare(strict_types=1);

namespace App\Tests\Domain\Factory;

use App\Domain\Entity\UserInterface;
use App\Domain\Factory\UserFactory;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;
use PHPUnit\Framework\TestCase;

class UserFactoryTest extends TestCase
{
    public function testCreate()
    {
        $passwordEncoder = $this->createMock(PasswordEncoderInterface::class);
        $passwordEncoder
            ->expects($this->once())
            ->method('encodePassword')
            ->willReturn('encoded password');

        $userRepository = $this->createMock(UserRepositoryInterface::class);
        $userRepository
            ->expects($this->at(0))
            ->method('isUsernameTaken')
            ->with('username')
            ->willReturn(true);
        $userRepository
            ->expects($this->at(1))
            ->method('isUsernameTaken')
            ->willReturn(false);

        $factory = new UserFactory($passwordEncoder, $userRepository);
        $this->assertInstanceOf(UserInterface::class, $factory->create(
            'username@example.com',
            'password',
            null,
            'first',
            'last',
            'files/1.jpg'
        ));
    }
}
