<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;

class SetUserPasswordPresenter implements PresenterInterface
{
    /** @var \App\Domain\Output\SetUserPasswordOutput */
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
