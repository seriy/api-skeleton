<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\UserInterface;
use App\Domain\Security\PasswordEncoderInterface;

class UserFactory
{
    private $passwordEncoder;

    public function __construct(PasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @throws \Exception
     */
    public function create(string $email, string $username, string $password): UserInterface
    {
        $user = (new User())
            ->setEmail($email)
            ->setUsername($username);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        return $user;
    }
}
