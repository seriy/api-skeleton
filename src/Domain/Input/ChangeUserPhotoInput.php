<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class ChangeUserPhotoInput implements InputInterface
{
    public $currentUserId;
    public $userId;
    public $photo;

    public function __construct(int $currentUserId, int $userId, string $photo)
    {
        $this->currentUserId = $currentUserId;
        $this->userId = $userId;
        $this->photo = $photo;
    }
}
