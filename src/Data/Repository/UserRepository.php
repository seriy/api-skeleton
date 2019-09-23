<?php

declare(strict_types=1);

namespace App\Data\Repository;

use App\Domain\Entity\User;
use App\Domain\Entity\UserInterface;
use App\Domain\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;

class UserRepository extends ServiceEntityRepository implements UserLoaderInterface, UserRepositoryInterface
{
    use QueryBuilderTrait;

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function getUser(int $userId): ?UserInterface
    {
        return $this->find($userId);
    }

    public function getUserByEmail(string $email): ?UserInterface
    {
        return $this->findOneBy(['email' => $email]);
    }

    public function getUserByGoogleId(string $googleId): ?UserInterface
    {
        return $this->findOneBy(['googleId' => $googleId]);
    }

    public function getUserByEmailConfirmationToken(string $token): ?UserInterface
    {
        return $this->findOneBy(['emailConfirmationToken' => $token]);
    }

    public function getUserByPasswordResettingToken(string $token): ?UserInterface
    {
        return $this->findOneBy(['passwordResettingToken' => $token]);
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($value): ?UserInterface
    {
        return $this->createQueryBuilder('u')
            ->where('u.email = :value OR u.username = :value')
            ->setParameter('value', $value)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isEmailTaken(string $email, int $ownerId = 0): bool
    {
        return null !== $this->createQueryBuilder('u')
            ->where('u.email = :email')
            ->andWhere('u.id != :userId')
            ->setParameter('email', $email)
            ->setParameter('userId', $ownerId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function isUsernameTaken(string $username, int $ownerId = 0): bool
    {
        return null !== $this->createQueryBuilder('u')
            ->where('u.username = :username')
            ->andWhere('u.id != :userId')
            ->setParameter('username', $username)
            ->setParameter('userId', $ownerId)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function getUsers(array $filters = [], array $sorts = [], int $limit = self::ITEMS_PER_REQUEST, int $offset = 0): array
    {
        return $this->getUsersQueryBuilder($filters, $sorts)
            ->setMaxResults($limit)
            ->setFirstResult($offset)
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getTotalUsers(array $filters = []): int
    {
        return (int) $this->getUsersQueryBuilder($filters)
            ->select('COUNT(u.id) AS total')
            ->getQuery()
            ->getSingleScalarResult();
    }

    private function getUsersQueryBuilder(array $filters = [], array $sorts = []): QueryBuilder
    {
        $builder = $this->createQueryBuilder('u')
            ->where('u.blockedTo IS NULL')
            ->andWhere('u.deletedAt IS NULL');

        $counter = 1;
        if (isset($filters['id'])) {
            $this->addWhere($builder, 'u.id', 'eq', $filters['id'], $counter);
        }

        if (isset($filters['username'])) {
            $this->addWhere($builder, 'u.username', 'like', $filters['username'], $counter);
        }

        if (isset($filters['firstName'])) {
            $this->addWhere($builder, 'u.firstName', 'like', $filters['firstName'], $counter);
        }

        if (isset($filters['lastName'])) {
            $this->addWhere($builder, 'u.lastName', 'like', $filters['lastName'], $counter);
        }

        foreach ($sorts as $value) {
            $this->addSort($builder, $value, 'u');
        }

        return $builder;
    }

    /**
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function saveUser(UserInterface $user): void
    {
        if (null === $user->getId()) {
            $this->_em->persist($user);
        }

        $this->_em->flush();
    }

    public function deleteUsers(): void
    {
        $this->createQueryBuilder('u')
            ->delete()
            ->where('DATE_ADD(u.deletedAt, :days, \'DAY\') < CURRENT_DATE()')
            ->setParameter('days', UserInterface::DAYS_BEFORE_USER_DELETION)
            ->getQuery()
            ->execute();
    }
}
