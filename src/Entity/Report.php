<?php

namespace App\Entity;

use App\Repository\ReportRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReportRepository::class)]
class Report
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contentReport = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?PastryChef $pastryChef = null;

    #[ORM\ManyToOne(inversedBy: 'reports')]
    private ?Client $client = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentReport(): ?string
    {
        return $this->contentReport;
    }

    public function setContentReport(?string $contentReport): static
    {
        $this->contentReport = $contentReport;

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
