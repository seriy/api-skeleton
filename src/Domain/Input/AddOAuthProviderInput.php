<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class AddOAuthProviderInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $provider;
    public $providerId;

    public function __construct(int $currentUserId, int $userId, string $provider, string $providerId)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->provider = $provider;
        $this->providerId = $providerId;
    }
}
