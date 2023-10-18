<?php

namespace App\Controller;

use App\Entity\CategoryPost;
use App\Entity\Post;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $posts = $entityManager->getRepository(Post::class)->findAll();

        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }

    #[Route('/post/create-random', name: 'app_post_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $title = 'Post title ' . rand(1,1240);
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($title)->lower();

        $category = $entityManager->getRepository(CategoryPost::class)->findRandomCategoryPost();

        $post = new Post();
        $post->setTitle($title);
        $post->setSlug($slug);
        $post->setDescription('Lorem ipsum dolor, sit amet consectetur adipisicing elit. Inventore quod enim voluptatibus excepturi suscipit tempora voluptas magnam ipsam. Eveniet amet quod a maiores, ut facere atque enim quis sequi reiciendis!');
        $post->setCategoryPost($category[0]);

        $entityManager->getRepository(Post::class)->save($post, true);

        return $this->redirectToRoute('app_post');
    }

    #[Route('/post/show/{slug}', name: 'app_post_show')]
    public function show(Post $post): Response
    {
        $comments = $post->getComments();

        return $this->render('post/show.html.twig', [
            'post' => $post,
            'comments' => $comments
        ]);
    }
}
