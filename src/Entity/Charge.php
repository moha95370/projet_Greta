<?php

namespace App\Entity;

use App\Repository\ChargeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChargeRepository::class)]
class Charge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $duration = null;

    #[ORM\Column]
    private ?float $price = null;

    #[ORM\ManyToMany(targetEntity: Station::class, inversedBy: 'charge_station')]
    private Collection $station;

    #[ORM\ManyToMany(targetEntity: Vehicle::class, inversedBy: 'charge_vehicle')]
    private Collection $vehicle;

    public function __construct()
    {
        $this->station = new ArrayCollection();
        $this->vehicle = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    /**
     * @return Collection<int, Station>
     */
    public function getStation(): Collection
    {
        return $this->station;
    }

    public function addStation(Station $station): static
    {
        if (!$this->station->contains($station)) {
            $this->station->add($station);
        }

        return $this;
    }

    public function removeStation(Station $station): static
    {
        $this->station->removeElement($station);

        return $this;
    }

    /**
     * @return Collection<int, Vehicle>
     */
    public function getVehicle(): Collection
    {
        return $this->vehicle;
    }

    public function addVehicle(Vehicle $vehicle): static
    {
        if (!$this->vehicle->contains($vehicle)) {
            $this->vehicle->add($vehicle);
        }

        return $this;
    }

    public function removeVehicle(Vehicle $vehicle): static
    {
        $this->vehicle->removeElement($vehicle);

        return $this;
    }
}
