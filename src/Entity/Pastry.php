<?php

namespace App\Entity;

use App\Repository\PastryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PastryRepository::class)]
class Pastry
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photoUrl = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\OneToMany(mappedBy: 'pastry', targetEntity: CommentPastry::class, cascade: ["remove"])]
    private Collection $commentPastries;

    #[ORM\ManyToOne(inversedBy: 'pastries', cascade: ["remove"])]
    private ?PastryChef $pastryChef = null;

    #[ORM\ManyToOne(inversedBy: 'pastries')]
    private ?Category $category = null;

    #[ORM\OneToMany(mappedBy: 'pastry', targetEntity: FavoritePastry::class)]
    private Collection $favoritePastries;

    public function __construct()
    {
        $this->commentPastries = new ArrayCollection();
        $this->favoritePastries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhotoUrl(): ?string
    {
        return $this->photoUrl;
    }

    public function setPhotoUrl(?string $photoUrl): static
    {
        $this->photoUrl = $photoUrl;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, CommentPastry>
     */
    public function getCommentPastries(): Collection
    {
        return $this->commentPastries;
    }

    public function addCommentPastry(CommentPastry $commentPastry): static
    {
        if (!$this->commentPastries->contains($commentPastry)) {
            $this->commentPastries->add($commentPastry);
            $commentPastry->setPastry($this);
        }

        return $this;
    }

    public function removeCommentPastry(CommentPastry $commentPastry): static
    {
        if ($this->commentPastries->removeElement($commentPastry)) {
            // set the owning side to null (unless already changed)
            if ($commentPastry->getPastry() === $this) {
                $commentPastry->setPastry(null);
            }
        }

        return $this;
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

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, FavoritePastry>
     */
    public function getFavoritePastries(): Collection
    {
        return $this->favoritePastries;
    }

    public function addFavoritePastry(FavoritePastry $favoritePastry): static
    {
        if (!$this->favoritePastries->contains($favoritePastry)) {
            $this->favoritePastries->add($favoritePastry);
            $favoritePastry->setPastry($this);
        }

        return $this;
    }

    public function removeFavoritePastry(FavoritePastry $favoritePastry): static
    {
        if ($this->favoritePastries->removeElement($favoritePastry)) {
            // set the owning side to null (unless already changed)
            if ($favoritePastry->getPastry() === $this) {
                $favoritePastry->setPastry(null);
            }
        }

        return $this;
    }
}
