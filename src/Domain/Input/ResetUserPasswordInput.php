<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class ResetUserPasswordInput implements InputInterface
{
    public $email;

    public function __construct(string $email)
    {
        $this->email = $email;
    }
}
