<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/comment/create/{postId}', name: 'app_comment_create')]
    public function create(EntityManagerInterface $entityManager, int $postId): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        $rand = rand(100,2000);
        $description = $rand . ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque fuga, facilis voluptates fugiat, sapiente enim quo provident nihil aspernatur unde, exercitationem assumenda id? Tempora distinctio possimus voluptatum! Laboriosam, architecto accusamus.';

        $comment = new Comment();
        $comment->setAuthor('Admin ' . $rand);
        $comment->setDescription($description);
        $comment->setPost($post);

        $entityManager->getRepository(Comment::class)->save($comment, true);

        return $this->redirectToRoute('app_post_show', ['slug' => $post->getSlug()]);
    }
}
