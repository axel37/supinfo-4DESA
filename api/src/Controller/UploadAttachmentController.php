<?php

// src/Controller/UploadAttachmentController.php

namespace App\Controller;

use App\Entity\Attachment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadAttachmentController extends AbstractController
{
    #[Route('/attachments', name: 'attachment_upload', methods: ['POST'])]
    public function __invoke(Request $request, EntityManagerInterface $entityManager): Response
    {
        $uploadedFile = $request->files->get('file');
        $postId = $request->request->get('postID'); // Récupérez l'ID du Post depuis la requête
        $description = $request->request->get('description');

        if (!$uploadedFile) {
            return $this->json(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        if (!$postId) {
            return $this->json(['error' => 'No post ID provided'], Response::HTTP_BAD_REQUEST);
        }

        $post = $entityManager->getRepository(Post::class)->find($postId);

        if (!$post) {
            return $this->json(['error' => 'Post not found'], Response::HTTP_NOT_FOUND);
        }

        $attachment = new Attachment();
        $attachment->setFile($uploadedFile);
        $attachment->setSystemName($uploadedFile->getClientOriginalName());
        $attachment->setPost($post); // Associez l'Attachment au Post
        $attachment->setDescription($description);

        $entityManager->persist($attachment);
        $entityManager->flush();

        return $this->json([
            'message' => 'File uploaded successfully',
            'id' => $attachment->getId()->__toString(),
        ]);
    }
}
