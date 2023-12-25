<?php

namespace App\Entity;

use App\Repository\CommentsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Discussions $discussion = null;

    #[ORM\ManyToOne(inversedBy: 'comments')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: Images::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'comment', targetEntity: CommentHashtags::class)]
    private Collection $commentHashtags;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->commentHashtags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getDiscussion(): ?Discussions
    {
        return $this->discussion;
    }

    public function setDiscussion(?Discussions $discussion): self
    {
        $this->discussion = $discussion;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): self
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * @return Collection<int, Images>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Images $image): self
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setComment($this);
        }

        return $this;
    }

    public function removeImage(Images $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getComment() === $this) {
                $image->setComment(null);
            }
        }

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
            $commentHashtag->setComment($this);
        }

        return $this;
    }

    public function removeCommentHashtag(CommentHashtags $commentHashtag): self
    {
        if ($this->commentHashtags->removeElement($commentHashtag)) {
            // set the owning side to null (unless already changed)
            if ($commentHashtag->getComment() === $this) {
                $commentHashtag->setComment(null);
            }
        }

        return $this;
    }
}
