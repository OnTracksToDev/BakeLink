<?php

namespace App\Entity;

use App\Repository\FavoritePastryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritePastryRepository::class)]
class FavoritePastry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoritePastries')]
    private ?Pastry $pastry = null;

    #[ORM\ManyToOne(inversedBy: 'favoritePastries')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPastry(): ?Pastry
    {
        return $this->pastry;
    }

    public function setPastry(?Pastry $pastry): static
    {
        $this->pastry = $pastry;

        return $this;
    }

    public function getClient(): ?Client
    {
        return $this->client;
    }

    public function setClient(?Client $client): static
    {
        $this->client = $client;

        return $this;
    }
}
