<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByEmail(string $email): User
    {
        return $this->createQueryBuilder('u')
           ->where('u.email = :email')
           ->setParameter(':email', $email)
           ->getQuery()
           ->getSingleResult();
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByPasswordResetToken(string $token): User
    {
        return $this->createQueryBuilder('u')
            ->select('u')
            ->from('App:PasswordReset', 'pr')
            ->where('pr.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getSingleResult();
    }
}
