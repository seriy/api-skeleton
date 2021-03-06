<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class RecoverUserInput implements InputInterface
{
    public $currentUserId;
    public $userId;

    public function __construct(int $currentUserId, int $userId)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
    }
}
