<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   // #[ORM\Column(length: 255)]
    //private ?string $idorganisateur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull]
    private ?string $description = null;

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotNull]
    
    private ?int $capacity = null;
   
    #[ORM\Column]
    #[Assert\NotBlank]
    private ?int $reserved = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    #[Assert\GreaterThan("today")]
    #[Assert\NotNull]
    private ?\DateTimeInterface $date = null;
    
    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotNull]
    private ?int $numberofdays = null;

    

    #[ORM\Column]
    #[Assert\Positive]
    #[Assert\NotNull]
    private ?float $prix = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $destination = null;

    #[ORM\Column(length: 255)]
    private ?string $image_file = null;

    #[ORM\OneToMany(mappedBy: 'idevent', targetEntity: Reservation::class)]
    private Collection $reservations;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    
    public function getId(): ?int
    {
        return $this->id;
    }

   /* public function getIdorganisateur(): ?string
    {
        return $this->idorganisateur;
    }

    public function setIdorganisateur(string $idorganisateur): static
    {
        $this->idorganisateur = $idorganisateur;

        return $this;
    }
*/
    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCapacity(): ?int
    {
        return $this->capacity;
    }

    public function setCapacity(int $capacity): static
    {
        $this->capacity = $capacity;

        return $this;
    }

    public function getReserved(): ?int
    {
        return $this->reserved;
    }

    public function setReserved(int $reserved): static
    {
        $this->reserved = $reserved;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getNumberofdays(): ?int
    {
        return $this->numberofdays;
    }

    public function setNumberofdays(int $numberofdays): static
    {
        $this->numberofdays = $numberofdays;

        return $this;
    }


    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
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

    public function getimage_file(): ?string
    {
        return $this->image_file;
    }

    public function setimage_file(string $image_file): static
    {
        $this->image_file = $image_file;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setIdevent($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getIdevent() === $this) {
                $reservation->setIdevent(null);
            }
        }

        return $this;
    }
}
