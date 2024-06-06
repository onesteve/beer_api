<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Brewery;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class BreweryRankingProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|Collection|null
    {
        return $this->entityManager->getRepository(Brewery::class)->getCountryRanking();
    }
}
