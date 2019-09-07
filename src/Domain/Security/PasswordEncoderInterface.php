<?php

declare(strict_types=1);

namespace App\Domain\Security;

use App\Domain\Entity\UserInterface;

interface PasswordEncoderInterface
{
    public function encodePassword(UserInterface $user, string $plainPassword): string;

    public function isPasswordValid(UserInterface $user, string $plainPassword): bool;
}
