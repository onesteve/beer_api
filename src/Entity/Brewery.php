<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\BreweryRepository;
use App\State\BreweryRankingProvider;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BreweryRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(
            uriTemplate: "/ranking",
            provider: BreweryRankingProvider::class
        ),
        new GetCollection(uriTemplate: '/'),
        new Post(uriTemplate: ''),
        new Get(uriTemplate: '/{id}'),
        new Delete(uriTemplate: '/{id}'),
        new Put(uriTemplate: '/{id}'),
        new Patch(uriTemplate: '/{id}')
    ],
    routePrefix: '/brewery'
)]
class Brewery
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $name = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    #[Assert\NotBlank]
    private ?string $website = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $street = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $city = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $zipcode = null;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale:8, nullable: true)]
    #[Assert\Range(min: -90, max: 90)]
    private ?string $latitude = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 11, scale:8, nullable: true)]
    #[Assert\Range(min: -180, max: 180)]
    private ?string $longitude = null;

    /**
     * @var Beer[]
     */
    #[ORM\OneToMany(mappedBy: 'brewery', targetEntity: Beer::class)]
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

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): void
    {
        $this->website = $website;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(?string $street): void
    {
        $this->street = $street;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): void
    {
        $this->city = $city;
    }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(?string $zipcode): void
    {
        $this->zipcode = $zipcode;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(?string $country): void
    {
        $this->country = $country;
    }

    public function getLatitude(): ?string
    {
        return $this->latitude;
    }

    public function setLatitude(?string $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): ?string
    {
        return $this->longitude;
    }

    public function setLongitude(?string $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getBeers(): array|Collection
    {
        return $this->beers;
    }

}