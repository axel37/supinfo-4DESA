<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\PostRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

/**
 * A user-generated post. Can contain text and/or files.
 */
#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiResource]
class Post
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    /**
     * @var string|null The text written by the user.
     */
    #[ORM\Column(length: 1000, nullable: true)]
    private ?string $textContent = null;

    /**
     * @var \DateTimeImmutable The original time of publication.
     */
    #[ORM\Column]
    private \DateTimeImmutable $postedAt;

    public function __construct()
    {
        $this->id = Uuid::v4();
        $this->postedAt = new \DateTimeImmutable();
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

}
