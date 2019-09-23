<?php

declare(strict_types=1);

namespace App\Domain\Repository;

use App\Domain\Entity\UserInterface;

interface UserRepositoryInterface extends RepositoryInterface
{
    public const ITEMS_PER_REQUEST = 20;

    public function getUser(int $userId): ?UserInterface;

    public function getUserByEmail(string $email): ?UserInterface;

    public function getUserByGoogleId(string $googleId): ?UserInterface;

    public function getUserByEmailConfirmationToken(string $token): ?UserInterface;

    public function getUserByPasswordResettingToken(string $token): ?UserInterface;

    public function isEmailTaken(string $email, int $ownerId = 0): bool;

    public function isUsernameTaken(string $username, int $ownerId = 0): bool;

    /**
     * @return \App\Domain\Entity\UserInterface[]
     */
    public function getUsers(array $filters = [], array $sorts = [], int $limit = self::ITEMS_PER_REQUEST, int $offset = 0): array;

    public function getTotalUsers(array $filters = []): int;

    public function saveUser(UserInterface $user): void;

    public function deleteUsers(): void;
}
