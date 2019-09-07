<?php

declare(strict_types=1);

namespace App\Domain\Output;

final class UserListOutput implements OutputInterface
{
    public $total = 0;

    /** @var \App\Domain\Entity\UserInterface[] */
    public $users = [];

    public function __construct(int $total, array $users)
    {
        $this->total = $total;
        $this->users = $users;
    }
}
