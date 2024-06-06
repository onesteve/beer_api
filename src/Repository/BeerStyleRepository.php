<?php

namespace App\Repository;

use App\Entity\BeerStyle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class BeerStyleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BeerStyle::class);
    }

    public function getStyleRanking(): array
    {
        $qb = $this->createQueryBuilder('s')
            ->innerJoin('s.beers', 'b')
            ->select('s.name, count(b.id) as quantity')
            ->groupBy('s.name')
            ->orderBy('quantity', 'desc')
        ;

        return $qb->getQuery()->execute();
    }
}
