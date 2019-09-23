<?php

declare(strict_types=1);

namespace App\Domain\Factory;

use App\Domain\Entity\User;
use App\Domain\Entity\UserInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\Security\PasswordEncoderInterface;
use App\Domain\Security\UsernameGenerator;

class UserFactory
{
    private $passwordEncoder;
    private $userRepository;

    public function __construct(
        PasswordEncoderInterface $passwordEncoder,
        UserRepositoryInterface $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    /**
     * @throws \Exception
     */
    public function create(
        string $email,
        string $password,
        string $username = null,
        string $firstName = null,
        string $lastName = null,
        string $photo = null
    ): UserInterface {
        $user = (new User())
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setPhoto($photo);

        if (null === $username) {
            $generator = new UsernameGenerator();
            $username = $generator->generateUsername($email);

            while ($this->userRepository->isUsernameTaken($username)) {
                $username = $generator->generateUsername($email, true);
            }
        }

        $user->setUsername($username);
        $user->setPassword($this->passwordEncoder->encodePassword($user, $password));

        return $user;
    }
}
