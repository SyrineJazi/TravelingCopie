<?php

namespace App\Entity;

use App\Repository\VoyageRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: VoyageRepository::class)]
class Voyage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Le nom est obligatoire !")]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Le prix est obligatoire !")]
    #[Assert\Positive(message:"Le prix doit être positif !")]
    private ?int $prix = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Veuillez choisir une destination")]
    private ?string $destination = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Veuillez choisir le type !")]
    private ?string $type = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_debut = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_fin = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Veuillez inserer une image !")]
    private ?string $image1 = null;

    #[ORM\OneToMany(mappedBy: 'voyage', targetEntity: Activite::class, orphanRemoval:true)]
    private Collection $activites;

    /**
     * @Assert\Callback
     */
    public function validateDateRange(ExecutionContextInterface $context)
    {
        // Ensure both dates are set before comparing
        if ($this->date_debut !== null && $this->date_fin !== null) {
            // Compare the dates
            if ($this->date_fin < $this->date_debut) {
                // Add a violation if date_fin is before date_debut
                $context->buildViolation('La date de fin doit être postérieure à la date de début.')
                    ->atPath('date_fin')
                    ->addViolation();
            }
        }
    }

    public function __construct()
    {
        $this->activites = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }
    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDestination(): ?string
    {
        return $this->destination;
    }

    public function setDestination(string $destination): static
    {
        $this->destination = $destination;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getDateDebut(): ?\DateTimeInterface
    {
        return $this->date_debut;
    }

    public function setDateDebut(?\DateTimeInterface $date_debut): static
    {
        $this->date_debut = $date_debut;

        return $this;
    }

    public function getDateFin(): ?\DateTimeInterface
    {
        return $this->date_fin;
    }

    public function setDateFin(?\DateTimeInterface $date_fin): static
    {
        $this->date_fin = $date_fin;

        return $this;
    }

    public function getImage1(): ?string
    {
        return $this->image1;
    }

    public function setImage1(?string $image1): static
    {
        $this->image1 = $image1;

        return $this;
    }

    /**
     * @return Collection<int, Activite>
     */
    public function getActivites(): Collection
    {
        return $this->activites;
    }

    public function addActivite(Activite $activite): static
    {
        if (!$this->activites->contains($activite)) {
            $this->activites->add($activite);
            $activite->setVoyage($this);
        }

        return $this;
    }

    public function removeActivite(Activite $activite): static
    {
        if ($this->activites->removeElement($activite)) {
            // set the owning side to null (unless already changed)
            if ($activite->getVoyage() === $this) {
                $activite->setVoyage(null);
            }
        }

        return $this;
    }

    
}
