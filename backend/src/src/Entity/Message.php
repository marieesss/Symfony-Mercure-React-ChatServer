<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $text = null;


    #[ORM\ManyToOne(targetEntity: Channel::class, inversedBy: 'channel')]
    private ?Channel $channel;


    #[ORM\ManytoOne(targetEntity: User::class,  inversedBy: 'user')]
    private ?User $user = null;

    #[ORM\PrePersist]
    public function setCreatedAtValue()
    {
    $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getUser(): ?int
    {
        return $this->user;
    }

    public function setUser(int $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getChannel(): ?int
    {
        return $this->channel;
    }

    public function setChannel(int $channel): static
    {
        $this->channel = $channel;

        return $this;
    }
}