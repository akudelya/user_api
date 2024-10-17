<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class UserToGroup
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    private User $user;
    
    #[ORM\ManyToOne(targetEntity: Group::class)]
    private Group $group;

    public function getId(): int
    {
        return $this->id;
    }
    
    public function setUser(User $user): void
    {
        $this->user = $user;
    }

    public function setGroup(Group $user): void
    {
        $this->group = $group;
    }
}