<?php

declare(strict_types=1);

namespace App\Tests\Domain\Entity;

use App\Domain\Entity\User;
use App\Domain\Entity\UserInterface;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use function implode;

class UserTest extends TestCase
{
    public function testId()
    {
        $user = new User();
        $this->assertNull($user->getId());
    }

    public function testGoogleId()
    {
        $user = new User();
        $this->assertNull($user->getGoogleId());

        $user->setGoogleId($googleId = '123456789');
        $this->assertEquals($googleId, $user->getGoogleId());
    }

    public function testEmail()
    {
        $user = new User();
        $this->assertNull($user->getEmail());

        $user->setEmail($email = 'username@example.com');
        $this->assertEquals($email, $user->getEmail());
    }

    public function testUsername()
    {
        $user = new User();
        $this->assertNull($user->getUsername());

        $user->setUsername($username = 'username');
        $this->assertEquals($username, $user->getUsername());
    }

    public function testPassword()
    {
        $user = new User();
        $this->assertNull($user->getPassword());

        $user->setPassword($password = 'password');
        $this->assertEquals($password, $user->getPassword());
    }

    public function testFirstName()
    {
        $user = new User();
        $this->assertNull($user->getFirstName());

        $user->setFirstName($firstName = 'first');
        $this->assertEquals($firstName, $user->getFirstName());
        $this->assertEquals($firstName, $user->getFullName());
    }

    public function testLastName()
    {
        $user = new User();
        $this->assertNull($user->getLastName());

        $user->setLastName($lastName = 'last');
        $this->assertEquals($lastName, $user->getLastName());
        $this->assertEquals($lastName, $user->getFullName());

        $user->setFirstName($firstName = 'first');
        $this->assertEquals(implode(' ', [$firstName, $lastName]), $user->getFullName());
    }

    public function testPhoto()
    {
        $user = new User();
        $this->assertEquals(UserInterface::DEFAULT_PHOTO, $user->getPhoto());

        $user->setPhoto($photo = 'files/1.jpg');
        $this->assertEquals($photo, $user->getPhoto());
    }

    public function testRoles()
    {
        $user = new User();
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertFalse($user->isAdmin());

        $user->setRoles($roles = ['ROLE_USER', 'ROLE_ADMIN']);
        $this->assertEquals($roles, $user->getRoles());
        $this->assertTrue($user->isAdmin());

        $user->setRoles([]);
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
        $this->assertFalse($user->isAdmin());
    }

    public function testDeviceTokens()
    {
        $user = new User();
        $this->assertEquals([], $user->getDeviceTokens());

        $user->setDeviceTokens($tokens = ['token']);
        $this->assertEquals($tokens, $user->getDeviceTokens());
    }

    public function testEmailConfirmed()
    {
        $user = new User();
        $this->assertFalse($user->isEmailConfirmed());

        $user->setEmailConfirmed(true);
        $this->assertTrue($user->isEmailConfirmed());
    }

    public function testEmailConfirmationToken()
    {
        $user = new User();
        $this->assertNull($user->getEmailConfirmationToken());

        $user->setEmailConfirmationToken($token = 'token');
        $this->assertEquals($token, $user->getEmailConfirmationToken());
    }

    public function testEmailConfirmationRequestedAt()
    {
        $user = new User();
        $this->assertNull($user->getEmailConfirmationRequestedAt());

        $user->setEmailConfirmationRequestedAt($requestedAt = new DateTimeImmutable());
        $this->assertEquals($requestedAt, $user->getEmailConfirmationRequestedAt());
    }

    public function testPasswordResettingToken()
    {
        $user = new User();
        $this->assertNull($user->getPasswordResettingToken());

        $user->setPasswordResettingToken($token = 'token');
        $this->assertEquals($token, $user->getPasswordResettingToken());
    }

    public function testPasswordResettingRequestedAt()
    {
        $user = new User();
        $this->assertNull($user->getPasswordResettingRequestedAt());

        $user->setPasswordResettingRequestedAt($requestedAt = new DateTimeImmutable());
        $this->assertEquals($requestedAt, $user->getPasswordResettingRequestedAt());
    }

    public function testBlocked()
    {
        $user = new User();
        $this->assertNull($user->getBlockedTo());
        $this->assertFalse($user->isBlocked());

        $user->setBlockedTo($blockedTo = new DateTimeImmutable());
        $this->assertEquals($blockedTo, $user->getBlockedTo());
        $this->assertTrue($user->isBlocked());
    }

    public function testDeleted()
    {
        $user = new User();
        $this->assertNull($user->getDeletedAt());
        $this->assertFalse($user->isDeleted());

        $user->setDeletedAt($deletedAt = new DateTimeImmutable());
        $this->assertEquals($deletedAt, $user->getDeletedAt());
        $this->assertTrue($user->isDeleted());
    }

    public function testCreated()
    {
        $user = new User();
        $this->assertInstanceOf(DateTimeImmutable::class, $user->getCreatedAt());
    }
}
