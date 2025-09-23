<?php

namespace App\Entity;

use App\Repository\ClientRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClientRepository::class)]
class Client extends User
{
    #[ORM\OneToMany(mappedBy: 'client', targetEntity: Report::class)]
    private Collection $reports;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: CommentPastry::class, cascade: ["remove"])]
    private Collection $commentPastries;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: RequestOrder::class, cascade: ["remove"])]
    private Collection $requestOrders;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: CommentPastryChef::class, cascade: ["remove"])]
    private Collection $commentPastryChefs;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: FavoritePastry::class)]
    private Collection $favoritePastries;

    #[ORM\OneToMany(mappedBy: 'client', targetEntity: FavoritePastryChef::class)]
    private Collection $favoritePastryChefs;

    public function __construct()
    {
        $this->reports = new ArrayCollection();
        $this->commentPastries = new ArrayCollection();
        $this->requestOrders = new ArrayCollection();
        $this->commentPastryChefs = new ArrayCollection();
        $this->favoritePastries = new ArrayCollection();
        $this->favoritePastryChefs = new ArrayCollection();
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
            $report->setClient($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getClient() === $this) {
                $report->setClient(null);
            }
        }

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
            $commentPastry->setClient($this);
        }

        return $this;
    }

    public function removeCommentPastry(CommentPastry $commentPastry): static
    {
        if ($this->commentPastries->removeElement($commentPastry)) {
            // set the owning side to null (unless already changed)
            if ($commentPastry->getClient() === $this) {
                $commentPastry->setClient(null);
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
            $requestOrder->setClient($this);
        }

        return $this;
    }

    public function removeRequestOrder(RequestOrder $requestOrder): static
    {
        if ($this->requestOrders->removeElement($requestOrder)) {
            // set the owning side to null (unless already changed)
            if ($requestOrder->getClient() === $this) {
                $requestOrder->setClient(null);
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
            $commentPastryChef->setClient($this);
        }

        return $this;
    }

    public function removeCommentPastryChef(CommentPastryChef $commentPastryChef): static
    {
        if ($this->commentPastryChefs->removeElement($commentPastryChef)) {
            // set the owning side to null (unless already changed)
            if ($commentPastryChef->getClient() === $this) {
                $commentPastryChef->setClient(null);
            }
        }

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
            $favoritePastry->setClient($this);
        }

        return $this;
    }

    public function removeFavoritePastry(FavoritePastry $favoritePastry): static
    {
        if ($this->favoritePastries->removeElement($favoritePastry)) {
            // set the owning side to null (unless already changed)
            if ($favoritePastry->getClient() === $this) {
                $favoritePastry->setClient(null);
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
            $favoritePastryChef->setClient($this);
        }

        return $this;
    }

    public function removeFavoritePastryChef(FavoritePastryChef $favoritePastryChef): static
    {
        if ($this->favoritePastryChefs->removeElement($favoritePastryChef)) {
            // set the owning side to null (unless already changed)
            if ($favoritePastryChef->getClient() === $this) {
                $favoritePastryChef->setClient(null);
            }
        }

        return $this;
    }
}
