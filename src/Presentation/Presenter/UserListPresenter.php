<?php

declare(strict_types=1);

namespace App\Presentation\Presenter;

use App\Domain\Presenter\PresenterInterface;

class UserListPresenter implements PresenterInterface
{
    /** @var \App\Domain\Output\UserListOutput */
    private $output;

    public function setOutput($output): void
    {
        $this->output = $output;
    }

    public function getOutput(): array
    {
        return [
            'meta' => ['total' => $this->output->total],
            'data' => $this->output->users,
        ];
    }
}
