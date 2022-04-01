<?php

namespace App\Repository;

use App\Entity\PasswordReset;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Doctrine\Persistence\ManagerRegistry;

class PasswordResetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PasswordReset::class);
    }

    /**
     * @throws NonUniqueResultException
     * @throws NoResultException
     */
    public function findByToken(string $token): PasswordReset
    {
        return $this->createQueryBuilder('pr')
            ->where('pr.token = :token')
            ->setParameter('token', $token)
            ->getQuery()
            ->getSingleResult();
    }
}
