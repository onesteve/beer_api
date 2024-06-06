<?php

namespace App\Repository;

use App\Entity\Brewery;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BreweryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Brewery::class);
    }

    public function getCountryRanking(): array
    {
        $qb = $this->createQueryBuilder('b')
            ->select('b.country, count(b.country) as quantity')
            ->groupBy('b.country')
            ->orderBy('quantity', 'desc')
        ;

        return $qb->getQuery()->execute();
    }
}
