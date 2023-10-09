<?php

namespace App\Controller;

use App\Service\CodeGenerator;
use App\Service\TopFiveGame;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index.home')]
    public function home(): Response
    {
        $templates_dir = $this->getParameter('templates_dir');
        dump($templates_dir);

        return $this->render('index/home.html.twig');
    }

    #[Route('/about', name: 'index.about')]
    public function about(): Response
    {
        dump('elo');
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

    #[Route('/generate-code', name: 'index.generate_code', methods: ['GET'])]
    public function generateCode(CodeGenerator $codeGenerator): Response
    {
        $code = $codeGenerator->generate();

        return $this->render('index/generate_code.html.twig', [
            'code' => $code
        ]);
    }

    #[Route('/top-random/{num}', name: 'index.top_random')]
    public function topRandom(TopFiveGame $topFiveGame, int $num = 5)
    {
        $games = $topFiveGame->random($num);

        return $this->render('index/top_random.html.twig', [
            'games' => $games
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