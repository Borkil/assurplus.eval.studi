<?php

namespace App\Entity;

use App\Repository\SinisterRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\ManyToOne(inversedBy: 'sinisters')]
    private ?Customer $customer = null;

    #[ORM\OneToMany(mappedBy: 'sinister', targetEntity: Images::class, orphanRemoval: true, cascade: ['persist'])]
    private Collection $Images;

    #[Assert\Count(max: 3, maxMessage: 'Le nombre de photo est limité à {{ limit }}')]
    #[Assert\All([
        new Assert\Image(mimeTypesMessage: 'Le fichier join n\'est pas une image')
    ])]
    private $imagesFiles;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->Images = new ArrayCollection();
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

    public function getCustomer(): ?Customer
    {
        return $this->customer;
    }

    public function setCustomer(?Customer $customer): self
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->Images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->Images->contains($image)) {
            $this->Images->add($image);
            $image->setSinister($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->Images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getSinister() === $this) {
                $image->setSinister(null);
            }
        }

        return $this;
    }

    public function getImagesFiles()
    {
        return $this->imagesFiles;
    }

    public function setImagesFiles($imagesFiles): self
    {
        foreach ($imagesFiles as $imageFile ) {
            $image = new Images();
            $image->setImageFile($imageFile);
            $this->addImage($image);
        }
        $this->imagesFiles = $imagesFiles;
        return $this;
    }
}
