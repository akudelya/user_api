<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\GroupRepository;
use \DateTimeImmutable;
use \InvalidArgumentException;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: 'groups')]
#[ApiResource]
#[ApiResource(
    uriTemplate: '/groups/{id}/users',
    uriVariables: [
        'id' => new Link(fromClass: Group::class),
    ],
    operations: [ new GetCollection() ]
)]
#[Get(provider: GroupContentProvider::class)]
#[GetCollection(provider: GroupContentProvider::class)]
#[ORM\HasLifecycleCallbacks]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[ORM\ManyToMany(targetEntity: User::class)]
    #[OrderBy(["name" => "ASC"])]
    private Collection $users;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    protected string $name;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $modifiedAt;
    

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $value): void
    {
        $this->name = $value;
    }
    
    public function getUsers(): Collection
    {
        return $this->users;
    }
    
    public function addUser(User $user): void
    {
        $ind = $this->findUserIndex($user);
        if ($ind == null) {
            $this->users[] = $user;
        }
    }

    public function deleteUser(User $user): void
    {
        $ind = $this->findUserIndex($user);
        if ($ind !== null) {
            unset($this->users[$ind]);
        }
    }
    
    public function findUserIndex(User $user): int|null
    {
        foreach ($this->users as $ind => $existingUser) {
            if ($existingUser->getId() == $user->getId()) {
                return $ind;
            }
        }
        return null;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $value): void
    {
        $this->createdAt = $value;
    }

    #[ORM\PrePersist]
    public function onPrePersist(PrePersistEventArgs $eventArgs)
    {
        $this->createdAt = new DateTimeImmutable();
        $this->modifiedAt = new DateTimeImmutable();
    }

    public function getModifiedAt(): DateTimeImmutable
    {
        return $this->modifiedAt;
    }

    public function setModifiedAt(DateTimeImmutable $value): void
    {
        $this->modifiedAt = $value;
    }
   
    #[ORM\PreUpdate]
    public function onPreUpdate(PreUpdateEventArgs $eventArgs)
    {
        $this->modifiedAt = new DateTimeImmutable();
    }
}