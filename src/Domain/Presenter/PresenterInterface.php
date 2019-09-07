<?php

declare(strict_types=1);

namespace App\Domain\Presenter;

interface PresenterInterface
{
    /**
     * @param \App\Domain\Output\OutputInterface $output
     */
    public function setOutput($output): void;

    /**
     * @return mixed
     */
    public function getOutput();
}
