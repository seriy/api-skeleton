<?php

declare(strict_types=1);

namespace App\Domain\Entity;

interface FileInterface
{
    public const NAME = 'files';
    public const IMAGE_MIME_TYPES = ['image/gif', 'image/jpeg', 'image/pjpeg', 'image/png'];

    public function getOriginalName(): string;

    public function getPath(): string;
}
