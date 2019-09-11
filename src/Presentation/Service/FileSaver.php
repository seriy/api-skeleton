<?php

declare(strict_types=1);

namespace App\Presentation\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use function mkdir;
use function realpath;
use function sha1;

class FileSaver
{
    public function save(UploadedFile $file, string $directory): array
    {
        @mkdir($path = __DIR__.'/../../../public/'.$directory, 0750, true);

        $name = sha1($file->getClientOriginalName().'-'.uniqid()).'.'.$file->guessExtension();
        $file->move(realpath($path), $name);

        return [
            'originalName' => $file->getClientOriginalName(),
            'path' => $directory.'/'.$name,
        ];
    }
}
