<?php

namespace App\Entity;

use App\Repository\HahstagsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HahstagsRepository::class)]
class Hahstags
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'hashtag', targetEntity: CommentHashtags::class)]
    private Collection $commentHashtags;

    public function __construct()
    {
        $this->commentHashtags = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection<int, CommentHashtags>
     */
    public function getCommentHashtags(): Collection
    {
        return $this->commentHashtags;
    }

    public function addCommentHashtag(CommentHashtags $commentHashtag): self
    {
        if (!$this->commentHashtags->contains($commentHashtag)) {
            $this->commentHashtags->add($commentHashtag);
            $commentHashtag->setHashtag($this);
        }

        return $this;
    }

    public function removeCommentHashtag(CommentHashtags $commentHashtag): self
    {
        if ($this->commentHashtags->removeElement($commentHashtag)) {
            // set the owning side to null (unless already changed)
            if ($commentHashtag->getHashtag() === $this) {
                $commentHashtag->setHashtag(null);
            }
        }

        return $this;
    }

   
}
