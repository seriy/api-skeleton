<?php

declare(strict_types=1);

namespace App\Domain\Entity;

class File implements FileInterface
{
    private $originalName;
    private $path;

    public function __construct(string $originalName, string $path)
    {
        $this->originalName = $originalName;
        $this->path = $path;
    }

    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    public function getPath(): string
    {
        return $this->path;
    }
}
