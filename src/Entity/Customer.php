<?php

namespace App\Entity;

use DateTimeImmutable;
use App\Entity\Sinister;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\CustomerRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


#[ORM\Entity(repositoryClass: CustomerRepository::class)]
class Customer
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

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
    private ?string $numberCustomer = null;

    #[ORM\Column(length: 50)]
    #[NotBlank(message: 'Le prénom ne peut être vide')]
    #[Assert\Length(
        min:3,
        minMessage: 'Le prénom ne peut pas faire moins de 3 caractères.',
        max:50,
        maxMessage: 'Le prénom ne peut pas faire plus de 50 caractères.'
    )]
    private ?string $firstname = null;

    #[ORM\Column(length: 50)]
    #[NotBlank(message: 'Le nom de famille ne peut être vide')]
    #[Assert\Length(
        min:3,
        minMessage: 'Le nom de famille ne peut pas faire moins de 3 caractères.',
        max:50,
        maxMessage: 'Le nom de famille ne peut pas faire plus de 50 caractères.'
    )]
    private ?string $lastname = null;

    #[ORM\Column(length: 10)]
    #[NotBlank(message: 'Le numéro de téléphone ne peut être vide.')]
    #[Assert\Regex(
        pattern:'(0[0-9]{9})', 
        message:'Le numéro de téléphone doit être au format Français.'
        )]
    #[Assert\Length(
        exactly:10,
        exactMessage: 'Le numéro de téléphone doit faire exactement 10 chiffres'
    )]
    private ?string $phoneNumber = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'l\'email ne peut pas être vide.')]
    #[Assert\Email(message: 'l\'adresse email {{ value }} n\’est pas une adresse email valide.')]
    private ?string $mail = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\OneToMany(mappedBy: 'customer', targetEntity: Sinister::class)]
    private Collection $sinisters;

    public function __construct()
    {
        $this->sinisters = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNumberCustomer(): ?string
    {
        return $this->numberCustomer;
    }

    public function setNumberCustomer(string $numberCustomer): self
    {
        $this->numberCustomer = $numberCustomer;

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

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

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
            $sinister->setCustomer($this);
        }

        return $this;
    }

    public function removeSinister(Sinister $sinister): self
    {
        if ($this->sinisters->removeElement($sinister)) {
            // set the owning side to null (unless already changed)
            if ($sinister->getCustomer() === $this) {
                $sinister->setCustomer(null);
            }
        }

        return $this;
    }

}
