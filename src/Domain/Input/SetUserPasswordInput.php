<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class SetUserPasswordInput implements InputInterface
{
    public $resettingToken;
    public $newPassword;

    public function __construct(string $resettingToken, string $newPassword)
    {
        $this->resettingToken = $resettingToken;
        $this->newPassword = $newPassword;
    }
}
