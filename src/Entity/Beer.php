<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\BeerRepository;
use App\State\BeerBitterestProvider;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: BeerRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: '/',
            order: ['name']
        ),
        new GetCollection(
            uriTemplate: "/ranking",
            order: ['abv' => 'desc']
        ),
        new GetCollection(
            uriTemplate: "/bitterest",
            provider: BeerBitterestProvider::class
        ),
        new Post(uriTemplate: ''),
        new Delete(uriTemplate: '/{id}'),
        new Put(uriTemplate: '/{id}'),
        new Patch(uriTemplate: '/{id}')
    ],
    routePrefix: '/beers'
)]
class Beer
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank]
    private ?float $abv = null;

    #[ORM\Column(type: Types::INTEGER)]
    #[Assert\NotBlank]
    private ?int $ibu = null;

    #[ORM\Column(type: Types::FLOAT, nullable: true)]
    private ?float $srm = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\Length(exactly: 12)]
    private ?string $upc = null;

    #[ORM\ManyToOne(targetEntity: Brewery::class, inversedBy: 'beers')]
    private ?Brewery $brewery = null;

    #[ORM\ManyToOne(targetEntity: BeerCategory::class, inversedBy: 'beers')]
    private ?BeerCategory $category = null;

    #[ORM\ManyToOne(targetEntity: BeerStyle::class, inversedBy: 'beers')]
    private ?BeerStyle $style = null;

    /**
     * @var Checkin[]
     */
    #[ORM\OneToMany(mappedBy: 'beer', targetEntity: Checkin::class)]
    private Collection|array $checkins;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getAbv(): ?float
    {
        return $this->abv;
    }

    public function setAbv(?float $abv): void
    {
        $this->abv = $abv;
    }

    public function getIbu(): ?int
    {
        return $this->ibu;
    }

    public function setIbu(?int $ibu): void
    {
        $this->ibu = $ibu;
    }

    public function getSrm(): ?float
    {
        return $this->srm;
    }

    public function setSrm(?float $srm): void
    {
        $this->srm = $srm;
    }

    public function getUpc(): ?string
    {
        return $this->upc;
    }

    public function setUpc(?string $upc): void
    {
        $this->upc = $upc;
    }

    public function getBrewery(): ?Brewery
    {
        return $this->brewery;
    }

    public function setBrewery(?Brewery $brewery): void
    {
        $this->brewery = $brewery;
    }

    public function getCategory(): ?BeerCategory
    {
        return $this->category;
    }

    public function setCategory(?BeerCategory $category): void
    {
        $this->category = $category;
    }

    public function getStyle(): ?BeerStyle
    {
        return $this->style;
    }

    public function setStyle(?BeerStyle $style): void
    {
        $this->style = $style;
    }

    public function getCheckins(): Collection|array
    {
        return $this->checkins;
    }
}