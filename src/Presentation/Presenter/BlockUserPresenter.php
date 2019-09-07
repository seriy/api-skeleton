<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;

class BlockUserPresenter implements PresenterInterface
{
    /** @var \App\Domain\Output\BlockUserOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput(): array
    {
        return [
            'data' => $this->output->user,
        ];
    }
}
