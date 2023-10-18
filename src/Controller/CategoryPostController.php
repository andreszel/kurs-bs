<?php

namespace App\Controller;

use App\Entity\CategoryPost;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

class CategoryPostController extends AbstractController
{
    #[Route('/category-post', name: 'app_category_post')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $categories = $entityManager->getRepository(CategoryPost::class)->findAll();

        return $this->render('category_post/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/category-post/show/{id}', name: 'app_category_post_show')]
    public function show(CategoryPost $category): Response
    {
        return $this->render('category_post/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category-post/create', name: 'app_category_post_create')]
    public function create(EntityManagerInterface $entityManager): Response
    {
        $name = 'Category ' . rand(1,1240);
        $slugger = new AsciiSlugger();
        $slug = $slugger->slug($name)->lower();

        $categoryPost = new CategoryPost();
        $categoryPost->setName($name);
        $categoryPost->setSlug($slug);

        $entityManager->persist($categoryPost);
        $entityManager->flush();

        return $this->redirectToRoute('app_category_post');
    }
}
