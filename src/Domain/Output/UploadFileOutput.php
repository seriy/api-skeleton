<?php

declare(strict_types=1);

namespace App\Domain\Output;

final class UploadFileOutput implements OutputInterface
{
    public $total = 0;

    /** @var \App\Domain\Entity\FileInterface[] */
    public $files = [];

    public function __construct(int $total, array $files)
    {
        $this->total = $total;
        $this->files = $files;
    }
}
