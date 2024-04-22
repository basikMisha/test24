<?php

namespace App\Entity;

use App\Repository\ClaimRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\PersistentCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ClaimRepository::class)]
class Claim
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: 'Это поле не должно быть пустым')]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Assert\NotBlank(message: 'Это поле не должно быть пустым')]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $text = null;

    #[ORM\ManyToOne(targetEntity: ClaimStatus::class)]
    #[ORM\JoinColumn(name: 'claimstatus_id', referencedColumnName: 'id')]
    private ClaimStatus|null $claimstatus = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private User|null $user = null;

    #[ORM\JoinTable(name: 'comment_to_claim')]
    #[ORM\JoinColumn(name: 'claim_id', referencedColumnName: 'id')]
    #[ORM\InverseJoinColumn(name: 'comment_id', referencedColumnName: 'id')]
    #[ORM\ManyToMany(targetEntity: 'App\Entity\Comment', cascade: ['persist'])]
    private ArrayCollection|PersistentCollection $comments;

    public function __construct(UserInterface|User $user)
    {
        $this->user = $user;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    //возвращет категорию, привязанную к этому комметарию
    public function getClaimStatus(): ?ClaimStatus
    {
        return $this->claimstatus;
    }

    public function setClaimStatus(?ClaimStatus $claimstatus): static
    {
        $this->claimstatus = $claimstatus;
        return $this;
    }

    public function getComments(): ArrayCollection|PersistentCollection
    {
        return $this->comments;
    }

    public function setComments(ArrayCollection $comments): static
    {
        $this->comments = $comments;
        return $this;
    }

    public function addComment(Comment $comment): void
    {
        $this->comments[] = $comment;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static 
    {
        $this->user = $user;
        return $this;
    }
}
