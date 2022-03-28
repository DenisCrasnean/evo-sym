<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Programme;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\QueryBuilder as QueryBuilderAlias;
use Doctrine\Persistence\ManagerRegistry;

class ProgrammeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Programme::class);
    }

    public function findAll(array $params = [])
    {
        $queryBuilder = $this->createQueryBuilder('p');

        if (null !== $params) {
            $query = $queryBuilder
                ->select('p.id', 'p.name', 'p.description', 'p.startTime', 'p.endTime', 'p.isOnline', 'p.maxParticipants');
            foreach ($params as $param) {
                $query = $this->applyFilters($queryBuilder, $params);
            }

            $query = $queryBuilder->getQuery();

            return $query->execute();
        }

        $query = $queryBuilder
            ->select('p.id', 'p.name', 'p.description', 'p.startDate', 'p.endDate', 'p.isOnline', 'p.maxParticipants');

        $query = $queryBuilder->getQuery();

        return $query->execute();
    }

    private function applyFilters($queryBuilder, $params)
    {
        if (isset($params['filterBy']) && isset($params['filterValue'])) {
            return $queryBuilder->where('p.' . $params['filterBy'] . "=" . "'" .$params['filterValue']."'");
        }

        if (isset($params['orderBy']) && isset($params['sortOrder'])) {
            return $queryBuilder->orderBy('p.' . $params['orderBy'], $params['sortOrder']);
        }
    }

    public function findByIsOnline(bool $isOnline)
    {
        $qb = $this->createQueryBuilder('p');
        $query = $qb->where('p.isOnline = :isOnline')
            ->setParameter('isOnline', $isOnline);

        $query = $qb->getQuery();

        return $query->execute();
    }
}
