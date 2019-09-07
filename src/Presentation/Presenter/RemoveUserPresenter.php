<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;

class RemoveUserPresenter implements PresenterInterface
{
    /** @var \App\Domain\Output\RemoveUserOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput()
    {
        return null;
    }
}
