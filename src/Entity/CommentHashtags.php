<?php

namespace App\Entity;

use App\Repository\CommentHashtagsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentHashtagsRepository::class)]
class CommentHashtags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentHashtags')]
    private ?Comments $comment = null;

    #[ORM\ManyToOne(inversedBy: 'commentHashtags')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Hahstags $hashtag = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getComment(): ?Comments
    {
        return $this->comment;
    }

    public function setComment(?Comments $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getHashtag(): ?Hahstags
    {
        return $this->hashtag;
    }

    public function setHashtag(?Hahstags $hashtag): self
    {
        $this->hashtag = $hashtag;

        return $this;
    }
}
