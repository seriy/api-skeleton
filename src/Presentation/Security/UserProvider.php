<?php

declare(strict_types=1);

namespace App\Presentation\Security;

use App\Domain\Entity\UserInterface as User;
use App\Domain\Repository\UserRepositoryInterface;
use LogicException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use UnexpectedValueException;
use function ctype_digit;
use function is_string;

class UserProvider implements UserProviderInterface
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loadUserByUsername($username): UserInterface
    {
        if (is_string($username) && !ctype_digit($username)) {
            throw new UnexpectedValueException('Check that option "user_identity_field" contains value "id".');
        }

        if (null === $user = $this->userRepository->getUser((int) $username)) {
            throw new UsernameNotFoundException();
        }

        return $user;
    }

    public function refreshUser(UserInterface $user): UserInterface
    {
        throw new LogicException('Check that firewall option "stateless" contains value "true".');
    }

    public function supportsClass($class): bool
    {
        return User::class === $class;
    }
}
