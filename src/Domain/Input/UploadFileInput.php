<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class UploadFileInput implements InputInterface
{
    public $currentUserId;
    public $files;

    public function __construct(int $currentUserId, array $files)
    {
        $this->currentUserId = $currentUserId;
        $this->files = $files;
    }
}
