<?php

namespace App\Controller;

use App\Entity\CategoryPost;
use App\Form\CategoryPostType;
use App\Repository\CategoryPostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryPostController extends AbstractController
{
    #[Route('/category-post', name: 'app_category_post')]
    public function index(CategoryPostRepository $categoryPostRepository): Response
    {
        return $this->render('category_post/index.html.twig', [
            'categories' => $categoryPostRepository->findAll(),
        ]);
    }

    #[Route('/category-post/show/{id}', name: 'app_category_post_show')]
    public function show(CategoryPost $category): Response
    {
        return $this->render('category_post/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category-post/new', name: 'app_category_post_new')]
    public function create(Request $request, CategoryPostRepository $categoryPostRepository): Response
    {
        $form = $this->createForm(CategoryPostType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $categoryPost = $form->getData();

            $categoryPostRepository->save($categoryPost, true);

            $this->addFlash('success', 'Successful create');

            return $this->redirectToRoute('app_category_post');
        }

        return $this->render('category_post\new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/category-post/edit/{slug}', name: 'app_category_post_edit')]
    public function edit(Request $request, CategoryPostRepository $categoryPostRepository, string $slug): Response
    {
        $categoryPost = $categoryPostRepository->findOneBy(['slug' => $slug]);

        if(!$categoryPost) {
            throw $this->createNotFoundException('Soory. Not found category!');
        }

        $form = $this->createForm(CategoryPostType::class, $categoryPost);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $categoryPost = $form->getData();

            $categoryPostRepository->save($categoryPost, true);

            $this->addFlash('success', 'Successful update');

            return $this->redirectToRoute('app_category_post');
        }

        return $this->render('category_post/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/category-post/delete/{slug}', methods: ['GET', 'DELETE'], name: 'app_category_post_delete')]
    public function delete(Request $request, CategoryPostRepository $categoryPostRepository, string $slug): Response
    {
        $categoryPost = $categoryPostRepository->findOneBy(['slug' => $slug]);

        $categoryPostRepository->remove($categoryPost, true);

        $this->addFlash('success', 'Successfull delete category!');

        return $this->redirectToRoute('app_category_post');
    }
}
