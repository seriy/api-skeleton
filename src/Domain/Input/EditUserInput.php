<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class EditUserInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $email;
    public $username;

    public function __construct(int $currentUserId, int $userId, string $email, string $username)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->email = $email;
        $this->username = $username;
    }
}
