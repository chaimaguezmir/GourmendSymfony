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



    #[Assert\NotBlank(message: "Le nom ne doit pas être vide")]
    #[Assert\Length(
        min: 4,
        minMessage: "Le nom doit avoir au moins 4 caractères"
    )]

    #[ORM\Column(length: 255)]
    private ?string $customerName = null;


    #[Assert\NotBlank(message: "Le numberPersonnes ne doit pas être vide")]
    #[Assert\Type(
        type: 'int',
        message: "Le numberPersonnes doit être une chaîne."
    )]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d+)?$/',
        message: "Le numberPersonnes doit être un nombre."
    )]
    #[ORM\Column]
    private ?int $numberPersonnes = null;

    #[Assert\NotBlank(message: "Le status ne doit pas être vide")]
    #[Assert\Length(
        min: 4,
        minMessage: "Le status doit avoir au moins 4 caractères"
    )]

    #[ORM\Column(length: 255)]
    private ?string $status = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date ne peut pas être vide')]
    #[Assert\GreaterThanOrEqual(value: "today", message: "La date doit être ultérieure à aujourd'hui")]
    private ?\DateTimeInterface $dateTime = null;


    #[ORM\ManyToOne(inversedBy: 'reservation')]
    private ?RestaurantTable $tableid = null;

    

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

    

    public function getTableid(): ?RestaurantTable
    {
        return $this->tableid;
    }

    public function setTableid(?RestaurantTable $tableid): static
    {
        $this->tableid = $tableid;

        return $this;
    }


    public function __toString(): string
    {
        return $this->getTableid()->getId(); 
    }
   
    
}
