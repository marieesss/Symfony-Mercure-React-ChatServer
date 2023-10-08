<?php

namespace App\Entity;

use App\Repository\ChannelRepository;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: ChannelRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Channel
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'channel')]
    private $users;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user')]
    private $messages;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->messages = new ArrayCollection();
    }

    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUsers(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setChannel($this);
        }

        return $this;
    }

    public function removeUsers(User $user): static
    {
        if ($this->users->removeElement($user)) {
            if ($user->getChannel() === $this) {
                $user->setChannel(null);
            }
        }

        return $this;
    }


    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages[] = $message;
            $message->setChannel($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            if ($message->getChannel() === $this) {
                $message->setChannel(null);
            }
        }

        return $this;
    }

}