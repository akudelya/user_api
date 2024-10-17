<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\UserRepository;
use \DateTimeImmutable;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
#[ApiResource]
#[ORM\HasLifecycleCallbacks]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    /**
     * Many Users have Many Groups.
     * @var Collection<int, Group>
     */
    #[ManyToMany(targetEntity: Group::class)]
    private Collection $groups;

    #[Assert\NotBlank]
    #[ORM\Column(length: 50)]
    protected string $name;

    #[Assert\NotBlank]
    #[Assert\Email]
    #[ORM\Column(length: 50)]
    protected string $email;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    protected DateTimeImmutable $modifiedAt;
    

    public function __construct()
    {
        $this->groups = new ArrayCollection();
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

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $value): void
    {
        $this->email = $value;
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

    public function getGroups(): Collection
    {
        return $this->groups;
    }
   
}