<?php

namespace App\Entity;

use App\Repository\LivraisonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LivraisonRepository::class)]
class Livraison
{
    #[ORM\Id]  //clé primaire
    #[ORM\GeneratedValue]  //auto increment
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_depart = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse_arrive = null;

    #[ORM\Column(length: 255)]   //ggghhhh
    private ?string $etat = null;

    #[ORM\Column]
    private ?int $personneId = null;

    #[ORM\OneToMany(mappedBy: 'livraison', targetEntity: Commande::class)]
    private Collection $commandes;

    public function __construct()
    {
        $this->commandes = new ArrayCollection();
    }

    
    // Assurez-vous de mettre à jour les méthodes addCommande et removeCommande en conséquence
    

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdresseDepart(): ?string
    {
        return $this->adresse_depart;
    }

    public function setAdresseDepart(string $adresse_depart): static
    {
        $this->adresse_depart = $adresse_depart;

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

    public function getPersonneId(): ?int
    {
        return $this->personneId;
    }

    public function setPersonneId(int $personneId): static
    {
        $this->personneId = $personneId;

        return $this;
    }

    /**
     * @return Collection<int, Commande>
     */
    public function getCommandes(): Collection
    {
        return $this->commandes;
    }

    public function addCommande(Commande $commande): self
    {
        if (!$this->commandes->contains($commande)) {
            $this->commandes[] = $commande;
            $commande->setLivraison($this);
        }

        return $this;
    }

    public function removeCommande(Commande $commande): self
    {
        if ($this->commandes->removeElement($commande)) {
            // set the owning side to null (unless already changed)
            if ($commande->getLivraison() === $this) {
                $commande->setLivraison(null);
            }
        }

        return $this;
    }


}
