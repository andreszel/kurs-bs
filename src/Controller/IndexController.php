<?php

namespace App\Controller;

use App\Message\SendKey;
use App\Message\SendSms;
use App\Service\CodeGeneratorDecorator;
use App\Service\RandomGame;
use App\Service\RandomUserId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Messenger\MessageBusInterface;

class IndexController extends AbstractController
{
    #[Route('/', name: 'index.home')]
    public function homepage(): Response
    {
        $templates_dir = $this->getParameter('templates_dir');

        /* $this->addFlash('success', 'alert success');
        $this->addFlash('info', 'alert info'); */

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

    #[Route('/generate-code', name: 'index.generate_code', methods: ['GET'])]
    public function generateCode(CodeGeneratorDecorator $codeGenerator): Response
    {
        $code = $codeGenerator->generate();

        return $this->render('index/generate_code.html.twig', [
            'code' => $code
        ]);
    }

    #[Route('/send-code', name: 'index.send_code')]
    public function sendCode(MessageBusInterface $bus, RandomUserId $randomUserId): JsonResponse
    {
        $bus->dispatch(new SendKey($randomUserId->getRandomUserId()));

        return new JsonResponse(['status' => 'Email sent']);
    }

    #[Route('/send-sms', name: 'index.send_sms')]
    public function sendSms(MessageBusInterface $bus, RandomUserId $randomUserId): JsonResponse
    {
        $bus->dispatch(new SendSms($randomUserId->getRandomUserId()));

        return new JsonResponse(['status' => 'Sms sent']);
    }

    #[Route('/top-random/{num}', name: 'index.top_random')]
    public function topRandom(RandomGame $randomGame, int $num = 5)
    {
        $games = $randomGame->random($num);

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