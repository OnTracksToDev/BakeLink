<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nameCategory = null;

    #[ORM\OneToMany(mappedBy: 'category', targetEntity: Pastry::class)]
    private Collection $pastries;

    public function __construct()
    {
        $this->pastries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameCategory(): ?string
    {
        return $this->nameCategory;
    }

    public function setNameCategory(?string $nameCategory): static
    {
        $this->nameCategory = $nameCategory;

        return $this;
    }

    /**
     * @return Collection<int, Pastry>
     */
    public function getPastries(): Collection
    {
        return $this->pastries;
    }

    public function addPastry(Pastry $pastry): static
    {
        if (!$this->pastries->contains($pastry)) {
            $this->pastries->add($pastry);
            $pastry->setCategory($this);
        }

        return $this;
    }

    public function removePastry(Pastry $pastry): static
    {
        if ($this->pastries->removeElement($pastry)) {
            // set the owning side to null (unless already changed)
            if ($pastry->getCategory() === $this) {
                $pastry->setCategory(null);
            }
        }

        return $this;
    }
}
