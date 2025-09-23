<?php

namespace App\Entity;

use App\Repository\FavoritePastryChefRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoritePastryChefRepository::class)]
class FavoritePastryChef
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favoritePastryChefs')]
    private ?PastryChef $pastryChef = null;

    #[ORM\ManyToOne(inversedBy: 'favoritePastryChefs')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPastryChef(): ?PastryChef
    {
        return $this->pastryChef;
    }

    public function setPastryChef(?PastryChef $pastryChef): static
    {
        $this->pastryChef = $pastryChef;

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
