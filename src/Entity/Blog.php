<?php

namespace App\Entity;
use App\Entity\Comm;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\BlogRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: BlogRepository::class)]
class Blog
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id= null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(min:5)]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"conetent text is required")]
    private ?string $content = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: true)]
    
    #[Assert\NotBlank(message:"Le lien de l'image ne peut pas être vide.")]
    private ?string $imageb = null;

    #[ORM\OneToMany(mappedBy: 'blog', targetEntity: Comm::class)]
    private Collection $comms;

    public function __construct()
    {
        $this->comms = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

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

    public function getImageb(): ?string
    {
        return $this->imageb;
    }

    public function setImageb(string $imageb): self
    {
        $this->imageb = $imageb;

        return $this;
    }
    public function __toString(): string
    {
        return $this->titre; // Renvoie le titre du blog
    }

    /**
     * @return Collection<int, Comms>
     */
    public function getComms(): Collection
    {
        return $this->comms;
    }

   #  public function addComment(Comments $comment): static
   # {
      #  if (!$this->comments->contains($comment)) {
        #    $this->comments->add($comment);
        #    $comment->setBlo($this); }

       # return $this;}

   # public function removeComment(Comments $comment): static
   # {if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
       #     if ($comment->getBlog() === $this) {
        #        $comment->setBlog(null); } }

       # return $this;}
}
