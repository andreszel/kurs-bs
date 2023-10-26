<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'app_category')]
    public function index(CategoryRepository $categoryRepository): Response
    {
        return $this->render('category/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
        ]);
    }

    #[Route('/category/new', name: 'app_category_new')]
    public function new(Request $request, CategoryRepository $categoryRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $form = $this->createForm(CategoryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $categoryRepository->save($category, true);

            $this->addFlash('success', 'Successful create');

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category\new.html.twig',[
            'form' => $form
        ]);
    }

    #[Route('/category/edit/{id}', name: 'app_category_edit')]
    public function edit(Request $request, CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find(['id' => $id]);

        if(!$category) {
            throw $this->createNotFoundException('Soory. Not found category!');
        }

        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $categoryRepository->save($category, true);

            $this->addFlash('success', 'Successful update');

            return $this->redirectToRoute('app_category');
        }

        return $this->render('category/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/category/show/{id}', name: 'app_category_show')]
    public function show(Category $category): Response
    {
        return $this->render('category/show.html.twig', [
            'category' => $category,
        ]);
    }

    #[Route('/category/delete/{id}', methods: ['GET', 'DELETE'], name: 'app_category_delete')]
    public function delete(Request $request, CategoryRepository $categoryRepository, int $id): Response
    {
        $category = $categoryRepository->find(['id' => $id]);

        $categoryRepository->remove($category, true);

        $this->addFlash('success', 'Successfull delete category!');

        return $this->redirectToRoute('app_category');
    }
}
