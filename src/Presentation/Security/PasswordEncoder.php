<?php

declare(strict_types=1);

namespace App\Presentation\Security;

use App\Domain\Entity\UserInterface;
use App\Domain\Security\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PasswordEncoder implements PasswordEncoderInterface
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function encodePassword(UserInterface $user, string $plainPassword): string
    {
        return $this->passwordEncoder->encodePassword($user, $plainPassword);
    }

    public function isPasswordValid(UserInterface $user, string $plainPassword): bool
    {
        return $this->passwordEncoder->isPasswordValid($user, $plainPassword);
    }
}
