<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class AddUserDeviceTokenInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $deviceToken;

    public function __construct(int $currentUserId, int $userId, string $deviceToken)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->deviceToken = $deviceToken;
    }
}
