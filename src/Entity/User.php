<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use Doctrine\Common\Collections\Collection;
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
    routePrefix: '/users'
)]
class User
{
    use TimestampableEntity;

    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    private ?string $plainPassword = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    #[ORM\Column(type: Types::STRING)]
    #[Assert\NotBlank]
    private ?string $username = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    #[Assert\NotBlank]
    private ?string $avatar = null;

    /**
     * @var Checkin[]
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Checkin::class)]
    private Collection|array $checkins;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;

        if (empty($roles)) {
            $roles[] = 'ROLE_USER';
        }

        return array_unique($roles);
    }

    public function addRole(string $role): void
    {
        if (!in_array($role, $this->roles)) {
            $this->roles[] = $role;
        }
    }

    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): void
    {
        $this->username = $username;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function __serialize(): array
    {
        return [$this->id, $this->email, $this->password];
    }

    public function __unserialize(array $data): void
    {
        [$this->id, $this->email, $this->password] = $data;
    }

    public function getCheckins(): Collection|array
    {
        return $this->checkins;
    }
}