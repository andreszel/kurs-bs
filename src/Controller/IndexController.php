<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index.home')]
    public function home(): Response
    {
        return $this->render('index/home.html.twig');
    }

    #[Route('/about', name: 'index.about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig');
    }

    #[Route('/hello/{firstName}', name: 'index.hello', methods: ['GET'])]
    public function hello(string $firstName = 'Guest'): Response
    {
        $favoriteGames = [
            'CS:GO',
            'Wow'
        ];

        return $this->render('index/hello.html.twig', [
            'firstName' => $firstName,
            'favoriteGames' => $favoriteGames
        ]);
    }

    #[Route('/top-game', name: 'index.top_game', methods: ['GET'])]
    public function topGame(): Response
    {
        $favoriteGames = [
            'CS:GO',
            'Wow'
        ];

        return $this->render('index/top_game.html.twig', [
            'favoriteGames' => $favoriteGames
        ]);
    }

    #[Route('/top', name: 'index.top')]
    public function top()
    {
        $topGames = [
            'CS:GO',
            'Wow'
        ];

        return new JsonResponse($topGames);
    }
}