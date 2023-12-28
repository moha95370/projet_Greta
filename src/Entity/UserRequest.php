<?php

namespace App\Entity;

use App\Repository\UserRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRequestRepository::class)]
class UserRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    private ?string $displayName = null;

    #[ORM\Column(length: 150)]
    private ?string $email = null;

    #[ORM\Column(length: 30)]
    private ?string $phone = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'userRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userRequests')]
    #[ORM\JoinColumn(nullable: false)]
    private ?TypeRequest $typeRequest = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDisplayName(): ?string
    {
        return $this->displayName;
    }

    public function setDisplayName(string $displayName): static
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isActive(): ?bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getTypeRequest(): ?TypeRequest
    {
        return $this->typeRequest;
    }

    public function setTypeRequest(?TypeRequest $typeRequest): static
    {
        $this->typeRequest = $typeRequest;

        return $this;
    }
}
