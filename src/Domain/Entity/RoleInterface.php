<?php

declare(strict_types=1);

namespace App\Domain\Entity;

interface RoleInterface
{
    public const USER = 'ROLE_USER';
    public const ADMIN = 'ROLE_ADMIN';
}
