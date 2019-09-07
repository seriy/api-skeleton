<?php

declare(strict_types=1);

namespace App\Domain\Output;

use App\Domain\Entity\UserInterface;

final class ResetUserPasswordOutput implements OutputInterface
{
    public $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }
}
