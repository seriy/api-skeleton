<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class RemoveOAuthProviderInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $provider;

    public function __construct(int $currentUserId, int $userId, string $provider)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->provider = $provider;
    }
}
