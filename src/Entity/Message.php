<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $contentMessage = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?int $sender = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?RequestOrder $requestOrder = null;
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContentMessage(): ?string
    {
        return $this->contentMessage;
    }

    public function setContentMessage(?string $contentMessage): static
    {
        $this->contentMessage = $contentMessage;

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

    public function getSender(): ?int
    {
        return $this->sender;
    }

    public function setSender(?int $sender): static
    {
        $this->sender = $sender;

        return $this;
    }

    public function getRequestOrder(): ?RequestOrder
    {
        return $this->requestOrder;
    }

    public function setRequestOrder(?RequestOrder $requestOrder): static
    {
        $this->requestOrder = $requestOrder;

        return $this;
    }
}
