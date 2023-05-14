<?php

namespace App\Entity;

use App\Repository\UserRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message: 'l\'email ne peut pas être vide.')]
    #[Assert\Email(message: 'l\'adresse email {{ value }} n\’est pas une adresse email valide.')]
    private ?string $email = null;

    #[ORM\Column(length: 6)]
    #[Assert\NotBlank(message:'Le numéro client ne peut pas être vide.')]
    #[Assert\Regex(
        pattern: '/[a-zA-Z0-9]{6}/',
        message : 'Le numéro de client peut contenir seulement des chiffres de 0 à 9 et des lettres de a à z.'
        )]
    #[Assert\Length(
        exactly: 6,
        exactMessage: 'Le numéro de client doit faire 6 caractères.'
        )]
    private ?string $numberUser = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le prénom ne peut être vide')]
    #[Assert\Length(
        min:3,
        minMessage: 'Le prénom ne peut pas faire moins de 3 caractères.',
        max:50,
        maxMessage: 'Le prénom ne peut pas faire plus de 50 caractères.'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le nom de famille ne peut être vide')]
    #[Assert\Length(
        min:3,
        minMessage: 'Le nom de famille ne peut pas faire moins de 3 caractères.',
        max:50,
        maxMessage: 'Le nom de famille ne peut pas faire plus de 50 caractères.'
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotBlank(message: 'Le numéro de téléphone ne peut être vide.')]
    #[Assert\Regex(
        pattern:'(0[0-9]{9})', 
        message:'Le numéro de téléphone doit être au format Français.'
        )]
    #[Assert\Length(
        exactly:10,
        exactMessage: 'Le numéro de téléphone doit faire exactement 10 chiffres'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'UserCustomer', targetEntity: Sinister::class, orphanRemoval: true)]
    private Collection $sinisters;

    public function __construct(){
        $this->createdAt = new DateTimeImmutable();
        $this->sinisters = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getNumberUser(): ?string
    {
        return $this->numberUser;
    }

    public function setNumberUser(string $numberUser): self
    {
        $this->numberUser = $numberUser;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Sinister>
     */
    public function getSinisters(): Collection
    {
        return $this->sinisters;
    }

    public function addSinister(Sinister $sinister): self
    {
        if (!$this->sinisters->contains($sinister)) {
            $this->sinisters->add($sinister);
            $sinister->setUserCustomer($this);
        }

        return $this;
    }

    public function removeSinister(Sinister $sinister): self
    {
        if ($this->sinisters->removeElement($sinister)) {
            // set the owning side to null (unless already changed)
            if ($sinister->getUserCustomer() === $this) {
                $sinister->setUserCustomer(null);
            }
        }

        return $this;
    }
}
