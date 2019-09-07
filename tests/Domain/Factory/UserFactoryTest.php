<?php

declare(strict_types=1);

namespace App\Tests\Domain\Factory;

use App\Domain\Entity\UserInterface;
use App\Domain\Factory\UserFactory;
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

        $factory = new UserFactory($passwordEncoder);
        $this->assertInstanceOf(UserInterface::class, $factory->create(
            'username@example.com',
            'username',
            'password'
        ));
    }
}
