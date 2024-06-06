<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\BeerStyleRepository;
use App\State\BeerStyleRankingProvider;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BeerStyleRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/ranking",
            provider: BeerStyleRankingProvider::class
        ),
        new GetCollection(uriTemplate: '/'),
        new Post(uriTemplate: ''),
        new Get(uriTemplate: '/{id}'),
        new Delete(uriTemplate: '/{id}'),
        new Put(uriTemplate: '/{id}'),
        new Patch(uriTemplate: '/{id}')
    ],
    routePrefix: '/beers/styles'
)]
class BeerStyle
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $name = null;

    /**
     * @var Beer[]
     */
    #[ORM\OneToMany(mappedBy: 'style', targetEntity: Beer::class)]
    private Collection|array $beers;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getBeers(): array|Collection
    {
        return $this->beers;
    }

}