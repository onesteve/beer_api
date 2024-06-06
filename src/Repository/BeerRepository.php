<?php

namespace App\Repository;

use App\Entity\Beer;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BeerRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Beer::class);
    }

    public function findBitterestBeers(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->where('b.ibu = :maxIbu')
            ->setParameter(
                'maxIbu',
                $this->createQueryBuilder('b2')
                    ->select('max(b2.ibu)')
                    ->getQuery()->getSingleScalarResult()
            )
        ;

        return $qb->getQuery()->execute();
    }
}
