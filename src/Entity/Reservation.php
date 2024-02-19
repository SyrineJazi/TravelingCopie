<?php

namespace App\Entity;

use App\Repository\ReservationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReservationRepository::class)]
class Reservation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Positive]
    private ?int $nbrreservation = null;
   
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $modepaiement = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $message = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $type = null;

    #[ORM\ManyToOne(inversedBy: 'reservations')]
    private ?Event $idevent = null;

    #[ORM\Column(length: 255)]
    private ?string $eventName = null;

    #[ORM\Column]
    private ?float $prixE = null;

    #[ORM\Column]
    private ?float $prixTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNbrreservation(): ?int
    {
        return $this->nbrreservation;
    }

    public function setNbrreservation(int $nbrreservation): static
    {
        $this->nbrreservation = $nbrreservation;

        return $this;
    }

    public function getModepaiement(): ?string
    {
        return $this->modepaiement;
    }

    public function setModepaiement(string $modepaiement): static
    {
        $this->modepaiement = $modepaiement;

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): static
    {
        $this->message = $message;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getIdevent(): ?Event
    {
        return $this->idevent;
    }

    public function setIdevent(?Event $idevent): static
    {
        $this->idevent = $idevent;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->eventName;
    }

    public function setEventName(string $eventName): static
    {
        $this->eventName = $eventName;

        return $this;
    }

    public function getPrixE(): ?float
    {
        return $this->prixE;
    }

    public function setPrixE(float $prixE): static
    {
        $this->prixE = $prixE;

        return $this;
    }

    public function getPrixTotal(): ?float
    {
        return $this->prixTotal;
    }

    public function setPrixTotal(float $prixTotal): static
    {
        $this->prixTotal = $prixTotal;

        return $this;
    }
}
