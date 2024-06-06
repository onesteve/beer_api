<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\Beer;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;

final class BeerBitterestProvider implements ProviderInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): array|Collection|null
    {
        return $this->entityManager->getRepository(Beer::class)->findBitterestBeers();
    }
}
