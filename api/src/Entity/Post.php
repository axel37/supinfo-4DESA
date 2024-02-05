<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Put;
use App\Repository\PostRepository;
use App\State\AssociateUserToPostProcessor;
use App\State\UserPasswordHasher;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;

/**
 * A user-generated post. Can contain text and/or files.
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource(
    operations: [
        new GetCollection(),
        new \ApiPlatform\Metadata\Post(processor: AssociateUserToPostProcessor::class),
        new Get(),
        new Put(),
        new Patch(),
        new Delete(),
    ],
    normalizationContext: ['groups' => ['post:read']],
    denormalizationContext: ['groups' => ['post:write']],
)]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    /**
     * @var string|null The text written by the user.
     */
    #[ORM\Column(length: 1000, nullable: true)]
    #[NotBlank]
    #[Groups(['post:read', 'post:write'])]
    private ?string $textContent = null;

    /**
     * @var \DateTimeImmutable The original time of publication.
     */
    #[ORM\Column]
    #[Groups(['post:read'])]
    private \DateTimeImmutable $postedAt;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Attachment::class, orphanRemoval: true)]
    #[Groups(['post:read'])]
    private Collection $attachments;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['post:read'])]
    // TODO : Ne pas demander author lors de la création (ça doit être automatique)
    private ?User $author = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->postedAt = new \DateTimeImmutable();
        $this->attachments = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getTextContent(): ?string
    {
        return $this->textContent;
    }

    public function setTextContent(?string $textContent): static
    {
        $this->textContent = $textContent;

        return $this;
    }

    public function getPostedAt(): \DateTimeImmutable
    {
        return $this->postedAt;
    }

    /**
     * @return Collection<int, Attachment>
     */
    public function getAttachments(): Collection
    {
        return $this->attachments;
    }

    public function addAttachment(Attachment $attachment): static
    {
        if (!$this->attachments->contains($attachment)) {
            $this->attachments->add($attachment);
            $attachment->setPost($this);
        }

        return $this;
    }

    public function removeAttachment(Attachment $attachment): static
    {
        if ($this->attachments->removeElement($attachment)) {
            // set the owning side to null (unless already changed)
            if ($attachment->getPost() === $this) {
                $attachment->setPost(null);
            }
        }

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): static
    {
        $this->author = $author;

        return $this;
    }

}
