<?php

declare(strict_types=1);

namespace App\Domain\Entity;

use DateTimeImmutable;
use Symfony\Component\Security\Core\User\UserInterface as BaseInterface;

/**
 * The inheritance from the UserInterface of Security Component in the Domain layer violates
 * the concept of Clean Architecture, but was added to make it possible to use library features.
 */
interface UserInterface extends BaseInterface, EntityInterface
{
    public const NAME = 'users';
    public const DEFAULT_PHOTO = 'files/default.png';
    public const DAYS_BEFORE_USER_DELETION = 30;
    public const SECONDS_BEFORE_TOKEN_EXPIRATION = 3600;
    public const SUPPORTED_OAUTH_PROVIDERS = ['google'];

    public function getId(): ?int;

    public function getGoogleId(): ?string;

    public function setGoogleId(?string $googleId);

    public function getEmail(): ?string;

    public function setEmail(string $email);

    public function getUsername(): ?string;

    public function setUsername(string $username);

    public function getPassword(): ?string;

    public function setPassword(string $password);

    public function getFirstName(): ?string;

    public function setFirstName(string $firstName);

    public function getLastName(): ?string;

    public function setLastName(string $lastName);

    public function getFullName(): string;

    public function getPhoto(): string;

    public function setPhoto(?string $photo);

    /**
     * @return string[]
     */
    public function getRoles(): array;

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles);

    public function isAdmin(): bool;

    /**
     * @return string[]
     */
    public function getDeviceTokens(): array;

    /**
     * @param string[] $deviceTokens
     */
    public function setDeviceTokens(array $deviceTokens);

    public function addDeviceToken(string $deviceToken);

    public function setEmailConfirmed(bool $confirmed);

    public function isEmailConfirmed(): bool;

    public function setEmailConfirmationToken(?string $token);

    public function getEmailConfirmationToken(): ?string;

    public function setEmailConfirmationRequestedAt(?DateTimeImmutable $requestedAt);

    public function getEmailConfirmationRequestedAt(): ?DateTimeImmutable;

    public function setPasswordResettingToken(?string $token);

    public function getPasswordResettingToken(): ?string;

    public function setPasswordResettingRequestedAt(?DateTimeImmutable $requestedAt);

    public function getPasswordResettingRequestedAt(): ?DateTimeImmutable;

    public function getBlockedTo(): ?DateTimeImmutable;

    public function setBlockedTo(?DateTimeImmutable $blockedTo);

    public function isBlocked(): bool;

    public function getDeletedAt(): ?DateTimeImmutable;

    public function setDeletedAt(?DateTimeImmutable $deletedAt);

    public function isDeleted(): bool;

    public function getCreatedAt(): DateTimeImmutable;
}
