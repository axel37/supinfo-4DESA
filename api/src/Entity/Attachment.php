<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\Controller\UploadAttachmentController;
use App\Repository\AttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Serializer\Annotation\Groups;
use App\Entity\Post as PostEntity;

/**
 * A file attached to a Post.
 */
#[ORM\Entity(repositoryClass: AttachmentRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['media_object:read']],
    operations: [
        new Get(),
        new GetCollection(),
        new Post(
            controller: UploadAttachmentController::class,
            deserialize: false,
            validationContext: ['groups' => ['Default', 'media_object_create']],
            openapiContext: [
                'requestBody' => [
                    'content' => [
                        'multipart/form-data' => [
                            'schema' => [
                                'type' => 'object',
                                'properties' => [
                                    'file' => [
                                        'type' => 'string',
                                        'format' => 'binary',
                                    ],
                                    'description' => [
                                        'type' => 'string'
                                    ],
                                    'postID' => [
                                        'type' =>'uuid'
                                    ]
                                ],
                            ],
                        ],
                    ],
                ],
            ]
        ),
        new Put(),
        new Delete(),
        new Patch()
    ]
)]
#[Vich\Uploadable]
class Attachment
{
    #[ORM\Id]
    #[ORM\Column(type: 'uuid')]
    private Uuid $id;

    #[Vich\UploadableField(mapping: "attachment", fileNameProperty: "systemName")]
    #[Groups([ "media_object:create"])]
    private ?File $file = null;

    #[ORM\Column(length: 500)]
    #[Groups(["media_object:read"])]
    private ?string $systemName = null;

    #[ORM\Column(length: 500, nullable: true)]
    #[Groups(["media_object:read"])]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'attachments')]
    #[ORM\JoinColumn(nullable: false)]
    #[NotBlank]
    private ?PostEntity $post = null;

    #[ORM\Column(type: 'datetime_immutable', nullable: true)]
    #[Groups(["media_object:read"])]
    private ?\DateTimeImmutable $updatedAt = null;

    public function __construct()
    {
        $this->id = Uuid::v4();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSystemName(): ?string
    {
        return $this->systemName;
    }

    public function setSystemName(?string $systemName): self
    {
        $this->systemName = $systemName;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPost(): ?PostEntity
    {
        return $this->post;
    }

    public function setPost(?PostEntity $post): self
    {
        $this->post = $post;

        return $this;
    }

    public function getFile(): ?File
    {
        return $this->file;
    }

    public function setFile(?File $file = null): void
    {
        $this->file = $file;

        if (null !== $file) {
            // It is required to at least update an "updatedAt" field to trigger the update event
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
