<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $customerName = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'int')]
    #[ORM\Column]
    private ?int $personneId = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: 'int')]
    #[ORM\Column]
    private ?int $numberPersonnes = null;

    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Assert\NotBlank]
    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $dateTime = null;


    #[ORM\OneToMany(mappedBy: 'reservationId', targetEntity: RestaurantTable::class)]
    private Collection $restaurantTables;

    public function __construct()
    {
        $this->restaurantTables = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(string $customerName): static
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getPersonneId(): ?int
    {
        return $this->personneId;
    }

    public function setPersonneId(int $personneId): static
    {
        $this->personneId = $personneId;

        return $this;
    }

    public function getNumberPersonnes(): ?int
    {
        return $this->numberPersonnes;
    }

    public function setNumberPersonnes(int $numberPersonnes): static
    {
        $this->numberPersonnes = $numberPersonnes;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getDateTime(): ?\DateTimeInterface
    {
        return $this->dateTime;
    }

    public function setDateTime(\DateTimeInterface $dateTime): static
    {
        $this->dateTime = $dateTime;

        return $this;
    }

    /**
     * @return Collection<int, RestaurantTable>
     */
    public function getRestaurantTables(): Collection
    {
        return $this->restaurantTables;
    }

    public function addRestaurantTable(RestaurantTable $restaurantTable): static
    {
        if (!$this->restaurantTables->contains($restaurantTable)) {
            $this->restaurantTables->add($restaurantTable);
            $restaurantTable->setReservationId($this);
        }

        return $this;
    }

    public function removeRestaurantTable(RestaurantTable $restaurantTable): static
    {
        if ($this->restaurantTables->removeElement($restaurantTable)) {
            // set the owning side to null (unless already changed)
            if ($restaurantTable->getReservationId() === $this) {
                $restaurantTable->setReservationId(null);
            }
        }

        return $this;
    }
    public function __toString(): string
    {
        return $this->getCustomerName(); // Assuming there's a method getName() in the Categorie class
    }
}
