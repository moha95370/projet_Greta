<?php

namespace App\Entity;

use App\Repository\VehicleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: VehicleRepository::class)]
class Vehicle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $typePrise = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dateDetention = null;

    #[ORM\Column]
    #[Gedmo\Timestampable(on:"create")]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $active = null;

    #[ORM\ManyToOne(inversedBy: 'vehicles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Charge::class, mappedBy: 'vehicle')]
    private Collection $charge_vehicle;

    #[ORM\Column(length: 50)]
    private ?string $name = null;

    public function __construct()
    {
        $this->charge_vehicle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypePrise(): ?string
    {
        return $this->typePrise;
    }

    public function setTypePrise(string $typePrise): static
    {
        $this->typePrise = $typePrise;

        return $this;
    }

    public function getDateDetention(): ?\DateTimeInterface
    {
        return $this->dateDetention;
    }

    public function setDateDetention(\DateTimeInterface $dateDetention): static
    {
        $this->dateDetention = $dateDetention;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

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

    /**
     * @return Collection<int, Charge>
     */
    public function getChargeVehicle(): Collection
    {
        return $this->charge_vehicle;
    }

    public function addChargeVehicle(Charge $chargeVehicle): static
    {
        if (!$this->charge_vehicle->contains($chargeVehicle)) {
            $this->charge_vehicle->add($chargeVehicle);
            $chargeVehicle->addVehicle($this);
        }

        return $this;
    }

    public function removeChargeVehicle(Charge $chargeVehicle): static
    {
        if ($this->charge_vehicle->removeElement($chargeVehicle)) {
            $chargeVehicle->removeVehicle($this);
        }

        return $this;
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
}
