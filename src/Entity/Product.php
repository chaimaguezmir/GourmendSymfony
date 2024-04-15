<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;
    
    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[Assert\Length(
        min: 5,
        minMessage: "Le titre doit avoir au moins 5 caractères"
    )]

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $prod_name = null;

    #[Assert\NotBlank(message: "Le type ne doit pas être vide")]
    #[Assert\Length(
        min: 3,
        minMessage: "Le type doit avoir au moins 3 caractères"
    )]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $type = null;
    
    #[Assert\NotBlank(message: "Le stock ne doit pas être vide")]
    #[Assert\Type(
        type: 'string',
        message: "Le stock doit être une chaîne."
    )]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d+)?$/',
        message: "Le stock doit être un nombre."
    )]
    #[ORM\Column(length: 255)]
    private ?string $stock = null;

    #[Assert\NotBlank(message: "Le prix ne doit pas être vide")]
    #[Assert\Regex(
        pattern: '/^\d+(\.\d{1,2})?$/',
        message: "Le prix doit être un nombre qui peut contenir jusqu'à deux décimales."
    )]
    #[ORM\Column(type: "string", length: 255)]
    private ?string $price = null;

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: "Le titre ne doit pas être vide")]
    #[ORM\ManyToOne(inversedBy: 'products')]
    private ?Categorie $idCategorie = null;

    #[ORM\ManyToMany(targetEntity: Panier::class, mappedBy: 'productId')]
    private Collection $paniers;

    public function __construct()
    {
        $this->paniers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProdName(): ?string
    {
        return $this->prod_name;
    }

    public function setProdName(string $prod_name): static
    {
        $this->prod_name = $prod_name;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getStock(): ?string
    {
        return $this->stock;
    }

    public function setStock(string $stock): static
    {
        $this->stock = $stock;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getIdCategorie(): ?categorie
    {
        return $this->idCategorie;
    }

    public function setIdCategorie(?categorie $idCategorie): static
    {
        $this->idCategorie = $idCategorie;

        return $this;
    }

    /**
     * @return Collection<int, Panier>
     */
    public function getPaniers(): Collection
    {
        return $this->paniers;
    }

    public function addPanier(Panier $panier): static
    {
        if (!$this->paniers->contains($panier)) {
            $this->paniers->add($panier);
            $panier->addProductId($this);
        }

        return $this;
    }

    public function removePanier(Panier $panier): static
    {
        if ($this->paniers->removeElement($panier)) {
            $panier->removeProductId($this);
        }

        return $this;
    }
}
