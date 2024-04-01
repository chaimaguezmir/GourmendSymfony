<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_depar = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_arrive = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_reception = null;

    #[ORM\Column(nullable: true)]
    private ?int $personneId = null;

    #[ORM\Column(nullable: true)]
    private ?int $commandeId = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseDepar(): ?string
    {
        return $this->adresse_depar;
    }

    public function setAdresseDepar(string $adresse_depar): static
    {
        $this->adresse_depar = $adresse_depar;

        return $this;
    }

    public function getAdresseArrive(): ?string
    {
        return $this->adresse_arrive;
    }

    public function setAdresseArrive(string $adresse_arrive): static
    {
        $this->adresse_arrive = $adresse_arrive;

        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): static
    {
        $this->etat = $etat;

        return $this;
    }

    public function getDateReception(): ?\DateTimeInterface
    {
        return $this->date_reception;
    }

    public function setDateReception(?\DateTimeInterface $date_reception): static
    {
        $this->date_reception = $date_reception;

        return $this;
    }

    public function getPersonneId(): ?int
    {
        return $this->personneId;
    }

    public function setPersonneId(?int $personneId): static
    {
        $this->personneId = $personneId;

        return $this;
    }

    public function getCommandeId(): ?int
    {
        return $this->commandeId;
    }

    public function setCommandeId(?int $commandeId): static
    {
        $this->commandeId = $commandeId;

        return $this;
    }
}
