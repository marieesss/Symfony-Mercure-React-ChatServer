<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;




#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Le nom d'utilisateur ne peut pas être vide.")
     * @Assert\Length(min=3, minMessage="Le nom d'utilisateur doit comporter au moins 3 caractères.")
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $username = null;

    /**
     * @Assert\NotBlank(message="L'adresse e-mail ne peut pas être vide.")
     * @Assert\Email(message="L'adresse e-mail n'est pas valide.")
     */
    #[ORM\Column(length: 255, unique: true)]
    private ?string $mail = null;


    /**
     * @Assert\NotBlank(message="Le mot de passe ne peut pas être vide.")
     * @Assert\Length(min=6, minMessage="Le mot de passe doit comporter au moins {{ limit }} caractères.")
     */
    #[Assert\GreaterThan(6)]
    #[ORM\Column(length: 255)]
    private ?string $password = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getRoles(): array
    {
        // Retournez un tableau de rôles pour l'utilisateur ici, par exemple, ['ROLE_USER']
        return ['ROLE_USER'];
    }

    public function getUserIdentifier(): string
    {
        // Retournez l'identifiant de l'utilisateur ici, généralement, l'adresse e-mail
        return $this->getMail();
    }

}