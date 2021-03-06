<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use function implode;
use function in_array;
use function mb_strtoupper;
use function trim;

class User implements UserInterface
{
    private $id;
    private $googleId;
    private $email;
    private $username;
    private $password;
    private $firstName;
    private $lastName;
    private $photo;
    private $roles;
    private $deviceTokens;
    private $emailConfirmed;
    private $emailConfirmationToken;
    private $emailConfirmationRequestedAt;
    private $passwordResettingToken;
    private $passwordResettingRequestedAt;
    private $blockedTo;
    private $deletedAt;
    private $createdAt;

    public function __construct()
    {
        $this->roles = [RoleInterface::USER];
        $this->deviceTokens = [];
        $this->emailConfirmed = false;
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return trim(implode(' ', [$this->firstName, $this->lastName]));
    }

    public function getPhoto(): string
    {
        return $this->photo ?: self::DEFAULT_PHOTO;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function setRoles(array $roles): self
    {
        if (!$roles) {
            $this->roles = [RoleInterface::USER];
        }

        foreach ($roles as $role) {
            $this->addRole($role);
        }

        return $this;
    }

    public function addRole(string $role): self
    {
        if (!$this->hasRole($role)) {
            $this->roles[] = mb_strtoupper($role);
        }

        return $this;
    }

    public function hasRole(string $role): bool
    {
        return in_array(mb_strtoupper($role), $this->roles, true);
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RoleInterface::ADMIN);
    }

    public function getDeviceTokens(): array
    {
        return $this->deviceTokens;
    }

    public function setDeviceTokens(array $deviceTokens): self
    {
        foreach ($deviceTokens as $deviceToken) {
            $this->addDeviceToken($deviceToken);
        }

        return $this;
    }

    public function addDeviceToken(string $deviceToken): self
    {
        if (!$this->hasDeviceToken($deviceToken)) {
            $this->deviceTokens[] = $deviceToken;
        }

        return $this;
    }

    public function hasDeviceToken(string $deviceToken): bool
    {
        return in_array($deviceToken, $this->deviceTokens, true);
    }

    public function setEmailConfirmed(bool $confirmed): self
    {
        $this->emailConfirmed = $confirmed;

        return $this;
    }

    public function isEmailConfirmed(): bool
    {
        return $this->emailConfirmed;
    }

    public function setEmailConfirmationToken(?string $token): self
    {
        $this->emailConfirmationToken = $token;

        return $this;
    }

    public function getEmailConfirmationToken(): ?string
    {
        return $this->emailConfirmationToken;
    }

    public function setEmailConfirmationRequestedAt(?DateTimeImmutable $requestedAt): self
    {
        $this->emailConfirmationRequestedAt = $requestedAt;

        return $this;
    }

    public function getEmailConfirmationRequestedAt(): ?DateTimeImmutable
    {
        return $this->emailConfirmationRequestedAt;
    }

    public function setPasswordResettingToken(?string $token): self
    {
        $this->passwordResettingToken = $token;

        return $this;
    }

    public function getPasswordResettingToken(): ?string
    {
        return $this->passwordResettingToken;
    }

    public function setPasswordResettingRequestedAt(?DateTimeImmutable $requestedAt): self
    {
        $this->passwordResettingRequestedAt = $requestedAt;

        return $this;
    }

    public function getPasswordResettingRequestedAt(): ?DateTimeImmutable
    {
        return $this->passwordResettingRequestedAt;
    }

    public function getBlockedTo(): ?DateTimeImmutable
    {
        return $this->blockedTo;
    }

    public function setBlockedTo(?DateTimeImmutable $blockedTo): self
    {
        $this->blockedTo = $blockedTo;

        return $this;
    }

    public function isBlocked(): bool
    {
        return null !== $this->blockedTo;
    }

    public function getDeletedAt(): ?DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(?DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function isDeleted(): bool
    {
        return null !== $this->deletedAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }
}
