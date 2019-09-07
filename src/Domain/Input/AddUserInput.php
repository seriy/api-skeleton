<?php

declare(strict_types=1);

namespace App\Domain\Input;

final class AddUserInput implements InputInterface
{
    public $email;
    public $username;
    public $password;

    public function __construct(string $email, string $username, string $password)
    {
        $this->email = $email;
        $this->username = $username;
        $this->password = $password;
    }
}
