<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class ConfirmUserEmailInput implements InputInterface
{
    public $confirmationToken;

    public function __construct(string $confirmationToken)
    {
        $this->confirmationToken = $confirmationToken;
    }
}
