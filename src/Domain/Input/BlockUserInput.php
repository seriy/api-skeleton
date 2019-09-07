<?php

declare(strict_types=1);

namespace App\Domain\Input;

use DateTimeImmutable;

final class BlockUserInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $blockedTo;

    public function __construct(int $currentUserId, int $userId, DateTimeImmutable $blockedTo)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->blockedTo = $blockedTo;
    }
}
