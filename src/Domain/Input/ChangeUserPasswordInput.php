<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class ChangeUserPasswordInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $currentPassword;
    public $newPassword;

    public function __construct(int $currentUserId, int $userId, string $currentPassword, string $newPassword)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->currentPassword = $currentPassword;
        $this->newPassword = $newPassword;
    }
}
