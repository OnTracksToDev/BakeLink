<?php

namespace App\Entity;

use App\Repository\PastryChefRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PastryChefRepository::class)]
class PastryChef extends User
{

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $experience = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $price = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $speciality = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $websiteLink = null;

    #[ORM\OneToMany(mappedBy: 'pastryChef', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\OneToMany(mappedBy: 'pastryChef', targetEntity: CommentPastryChef::class, cascade: ["remove"])]
    private Collection $commentPastryChefs;

    #[ORM\OneToMany(mappedBy: 'pastryChef', targetEntity: RequestOrder::class, cascade: ["remove"])]
    private Collection $requestOrders;

    #[ORM\OneToMany(mappedBy: 'pastryChef', targetEntity: Pastry::class, cascade: ["remove"])]
    private Collection $pastries;

    #[ORM\OneToMany(mappedBy: 'pastryChef', targetEntity: FavoritePastryChef::class)]
    private Collection $favoritePastryChefs;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
        $this->commentPastryChefs = new ArrayCollection();
        $this->requestOrders = new ArrayCollection();
        $this->pastries = new ArrayCollection();
        $this->favoritePastryChefs = new ArrayCollection();
    }

    public function getExperience(): ?string
    {
        return $this->experience;
    }

    public function setExperience(?string $experience): static
    {
        $this->experience = $experience;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(?string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getSpeciality(): ?string
    {
        return $this->speciality;
    }

    public function setSpeciality(?string $speciality): static
    {
        $this->speciality = $speciality;

        return $this;
    }

    public function getWebsiteLink(): ?string
    {
        return $this->websiteLink;
    }

    public function setWebsiteLink(?string $websiteLink): static
    {
        $this->websiteLink = $websiteLink;

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setPastryChef($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getPastryChef() === $this) {
                $report->setPastryChef(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CommentPastryChef>
     */
    public function getCommentPastryChefs(): Collection
    {
        return $this->commentPastryChefs;
    }

    public function addCommentPastryChef(CommentPastryChef $commentPastryChef): static
    {
        if (!$this->commentPastryChefs->contains($commentPastryChef)) {
            $this->commentPastryChefs->add($commentPastryChef);
            $commentPastryChef->setPastryChef($this);
        }

        return $this;
    }

    public function removeCommentPastryChef(CommentPastryChef $commentPastryChef): static
    {
        if ($this->commentPastryChefs->removeElement($commentPastryChef)) {
            // set the owning side to null (unless already changed)
            if ($commentPastryChef->getPastryChef() === $this) {
                $commentPastryChef->setPastryChef(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, RequestOrder>
     */
    public function getRequestOrders(): Collection
    {
        return $this->requestOrders;
    }

    public function addRequestOrder(RequestOrder $requestOrder): static
    {
        if (!$this->requestOrders->contains($requestOrder)) {
            $this->requestOrders->add($requestOrder);
            $requestOrder->setPastryChef($this);
        }

        return $this;
    }

    public function removeRequestOrder(RequestOrder $requestOrder): static
    {
        if ($this->requestOrders->removeElement($requestOrder)) {
            // set the owning side to null (unless already changed)
            if ($requestOrder->getPastryChef() === $this) {
                $requestOrder->setPastryChef(null);
            }
        }

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
            $pastry->setPastryChef($this);
        }

        return $this;
    }

    public function removePastry(Pastry $pastry): static
    {
        if ($this->pastries->removeElement($pastry)) {
            // set the owning side to null (unless already changed)
            if ($pastry->getPastryChef() === $this) {
                $pastry->setPastryChef(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, FavoritePastryChef>
     */
    public function getFavoritePastryChefs(): Collection
    {
        return $this->favoritePastryChefs;
    }

    public function addFavoritePastryChef(FavoritePastryChef $favoritePastryChef): static
    {
        if (!$this->favoritePastryChefs->contains($favoritePastryChef)) {
            $this->favoritePastryChefs->add($favoritePastryChef);
            $favoritePastryChef->setPastryChef($this);
        }

        return $this;
    }

    public function removeFavoritePastryChef(FavoritePastryChef $favoritePastryChef): static
    {
        if ($this->favoritePastryChefs->removeElement($favoritePastryChef)) {
            // set the owning side to null (unless already changed)
            if ($favoritePastryChef->getPastryChef() === $this) {
                $favoritePastryChef->setPastryChef(null);
            }
        }

        return $this;
    }
}
