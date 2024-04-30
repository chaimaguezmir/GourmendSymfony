<?php

namespace App\Entity;

use App\Repository\ProductRatingRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProductRatingRepository::class)]
class ProductRating
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $nbrratting = null;

    #[ORM\ManyToOne(inversedBy: 'productRatings')]
    private ?Product $Product = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrratting(): ?int
    {
        return $this->nbrratting;
    }

    public function setNbrratting(int $nbrratting): static
    {
        $this->nbrratting = $nbrratting;

        return $this;
    }

    public function getProduct(): ?Product
    {
        return $this->Product;
    }

    public function setProduct(?Product $Product): static
    {
        $this->Product = $Product;

        return $this;
    }
}
