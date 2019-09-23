<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;

class UploadFilePresenter implements PresenterInterface
{
    /** @var \App\Domain\Output\UploadFileOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput(): array
    {
        return [
            'meta' => ['total' => $this->output->total],
            'data' => $this->output->files
        ];
    }
}
