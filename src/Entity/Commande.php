<?php

namespace App\Entity;

use App\Repository\CommandeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_dest = null;

    #[ORM\Column(length: 255)]
    private ?int $prix_total = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\Column]
    private ?int $idPersonne = null;



    #[ORM\ManyToOne(inversedBy: 'commande')]
    private ?Panier $panier = null;

    #[ORM\ManyToOne(inversedBy: 'commandes')]
    private ?Livraison $livraison = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAdresseDest(): ?string
    {
        return $this->adresse_dest;
    }

    public function setAdresseDest(string $adresse_dest): static
    {
        $this->adresse_dest = $adresse_dest;

        return $this;
    }

    public function getPrixTotal(): ?string
    {
        return $this->prix_total;
    }

    public function setPrixTotal(string $prix_total): static
    {
        $this->prix_total = $prix_total;

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

    public function getIdPersonne(): ?int
    {
        return $this->idPersonne;
    }

    public function setIdPersonne(int $idPersonne): static
    {
        $this->idPersonne = $idPersonne;

        return $this;
    }

 
    public function __toString(): string
    {
        return $this->getId(); 
    }

    public function getPanier(): ?Panier
    {
        return $this->panier;
    }

    public function setPanier(?Panier $panier): static
    {
        $this->panier = $panier;

        return $this;
    }

    public function getLivraison(): ?Livraison
    {
        return $this->livraison;
    }

    public function setLivraison(?Livraison $livraison): self
    {
        $this->livraison = $livraison;

        return $this;
    }
}
