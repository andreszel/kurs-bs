<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentType;
use App\Repository\CommentRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/post-comment-new/{postId}', name: 'app_post_comment_new')]
    public function new(Request $request, CommentRepository $commentRepository, PostRepository $postRepository, int $postId): Response
    {
        $post = $postRepository->find($postId);

        if(!$post) {
            throw $this->createNotFoundException('Sorry. You have a problem!');
        }

        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();

            $comment->setPost($post);

            $commentRepository->save($comment, true);

            $this->addFlash('success', 'Successfull create post comment!');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('comment/new.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route('/post-comment-edit/{id}', name: 'app_post_comment_edit')]
    public function edit(Request $request, CommentRepository $commentRepository, int $id): Response
    {
        $comment = $commentRepository->find($id);

        if(!$comment) {
            throw $this->createNotFoundException('not exist post');
        }

        $post = $comment->getPost();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $comment = $form->getData();

            $commentRepository->save($comment, true);

            $this->addFlash('success', 'Successfull update post comment!');

            return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
        }

        return $this->render('comment/edit.html.twig', [
            'form' => $form,
            'post' => $post
        ]);
    }

    #[Route('/post-comment-delete/{id}', methods: ['GET', 'DELETE'], name: 'app_post_comment_delete')]
    public function delete(Request $request, CommentRepository $commentRepository, int $id): Response
    {
        $comment = $commentRepository->find($id);

        $post = $comment->getPost();

        $commentRepository->remove($comment, true);

        $this->addFlash('success', 'Successfull delete comment!');

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }

    #[Route('/post-comment/create-random/{postId}', name: 'app_post_comment_create_random')]
    public function createRandom(EntityManagerInterface $entityManager, int $postId): Response
    {
        $post = $entityManager->getRepository(Post::class)->find($postId);

        $rand = rand(100,2000);
        $description = $rand . ' Lorem ipsum dolor sit amet consectetur adipisicing elit. Neque fuga, facilis voluptates fugiat, sapiente enim quo provident nihil aspernatur unde, exercitationem assumenda id? Tempora distinctio possimus voluptatum! Laboriosam, architecto accusamus.';

        $comment = new Comment();
        $comment->setAuthor('Admin ' . $rand);
        $comment->setDescription($description);
        $comment->setPost($post);

        $entityManager->getRepository(Comment::class)->save($comment, true);

        $this->addFlash('success', 'Successfull create random comment!');

        return $this->redirectToRoute('app_post_show', ['id' => $post->getId()]);
    }
}
