<?php

namespace App\Entity;

use App\Repository\SinisterRepository;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SinisterRepository::class)]
class Sinister
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:'l\'adresse du sinistre ne peut être vide')]
    private ?string $adressOfSinister = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Length(
        min: 20,
        minMessage: 'La déscription ne peut pas faire moins de {{ limit }} caractères',
    )]
    #[Assert\NotBlank(message:'La description ne peut pas être vide')]
    private ?string $description = null;

    #[ORM\Column(length: 9)]
    #[Assert\NotBlank(message : 'le numéro de plaque ne peut être vide')]
    #[Assert\Regex(
        pattern:'/[a-zA-Z]{2}[-][0-9]{3}[-][a-zA-Z]{2}/',
        message : 'Le numéro de plaque d\'immatriculation doit être au formmat aa-111-aa')]
    private ?string $numberRegistration = null;
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

        
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdressOfSinister(): ?string
    {
        return $this->adressOfSinister;
    }

    public function setAdressOfSinister(string $adressOfSinister): self
    {
        $this->adressOfSinister = $adressOfSinister;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getNumberRegistration(): ?string
    {
        return $this->numberRegistration;
    }

    public function setNumberRegistration(string $numberRegistration): self
    {
        $this->numberRegistration = $numberRegistration;

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
}
