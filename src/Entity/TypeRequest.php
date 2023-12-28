<?php

namespace App\Entity;

use App\Repository\TypeRequestRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TypeRequestRepository::class)]
class TypeRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $name = null;

    #[ORM\Column(length: 120)]
    #[Gedmo\Slug(fields: ['name'])]
    private ?string $slug = null;

    #[ORM\OneToMany(mappedBy: 'typeRequest', targetEntity: UserRequest::class, orphanRemoval: true)]
    private Collection $userRequests;

    public function __construct()
    {
        $this->userRequests = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * @return Collection<int, UserRequest>
     */
    public function getUserRequests(): Collection
    {
        return $this->userRequests;
    }

    public function addUserRequest(UserRequest $userRequest): static
    {
        if (!$this->userRequests->contains($userRequest)) {
            $this->userRequests->add($userRequest);
            $userRequest->setTypeRequest($this);
        }

        return $this;
    }

    public function removeUserRequest(UserRequest $userRequest): static
    {
        if ($this->userRequests->removeElement($userRequest)) {
            // set the owning side to null (unless already changed)
            if ($userRequest->getTypeRequest() === $this) {
                $userRequest->setTypeRequest(null);
            }
        }

        return $this;
    }
}
