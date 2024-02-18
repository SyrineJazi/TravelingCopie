<?php
namespace Egulias\EmailValidator\Parser;
use symfony\Egulias\EmailValidator\Parser\Comments as EmailComments;
use Egulias\EmailValidator\Parser\Comment;

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommentsRepository;
use Doctrine\DBAL\Types\Types;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Mime\Message;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id_c= null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message:"Le commentaire ne peut pas être vide.")]
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id_c;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(string $commentaire): static
    {
         if (!empty($commentaire)) {
              $this->commentaire = $commentaire;
            }
       // $this->commentaire = $commentaire;

        return $this;
    }
}
