<?php

namespace App\Entity;

use App\Repository\RestaurantTableRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RestaurantTableRepository::class)]
class RestaurantTable
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Le capacity ne doit pas être vide")]
    #[Assert\Type(
        type: 'int',
        message: "Le capacity doit être une chaîne."
    )]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d+)?$/',
        message: "Le capacity doit être un nombre."
    )]
    #[ORM\Column]
    private ?int $capacity = null;

    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 255)]
    #[ORM\Column(length: 255)]
    private ?string $available = null;

    #[Assert\NotBlank(message: "La description ne doit pas être vide")]
    #[Assert\Length(
        min: 8,
        minMessage: "La description doit avoir au moins 8 caractères"
    )]

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getAvailable(): ?string
    {
        return $this->available;
    }

    public function setAvailable(string $available): static
    {
        $this->available = $available;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }
    public function __toString(): string
    {
        return $this->getId(); 
    }
    
    public function markAsOccupied(): void
    {
        $this->available = 'occupée';
    }
    public function markAsDisponible(): void
    {
        $this->available = 'Disponible';
    }
   
}
