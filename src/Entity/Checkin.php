<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ApiResource(
    operations: [
        new GetCollection(uriTemplate: '/'),
        new Post(uriTemplate: ''),
        new Get(uriTemplate: '/{id}'),
        new Delete(uriTemplate: '/{id}'),
        new Put(uriTemplate: '/{id}'),
        new Patch(uriTemplate: '/{id}')
    ],
    routePrefix: '/checkin'
)]
class Checkin
{
    use TimestampableEntity;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::FLOAT)]
    #[Assert\NotBlank]
    private ?float $mark = null;

    #[ORM\ManyToOne(targetEntity: Beer::class, inversedBy: 'checkins')]
    private ?Beer $beer = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'checkins')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMark(): ?float
    {
        return $this->mark;
    }

    public function setMark(?float $mark): void
    {
        $this->mark = $mark;
    }

    public function getBeer(): ?Beer
    {
        return $this->beer;
    }

    public function setBeer(?Beer $beer): void
    {
        $this->beer = $beer;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): void
    {
        $this->user = $user;
    }
}