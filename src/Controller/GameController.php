<?php

namespace App\Controller;

use App\Entity\Game;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {
    }

    #[Route('/game', name: 'app_game')]
    public function index(): Response
    {
        $prefix = rand(20,200);
        $score = rand(6,10);
        $game = new Game();
        $game->setName('Game ' . $prefix);
        $game->setDescription('Lorem ipsum');
        $game->setScore($score);
        $game->setReleaseDate(new \DateTime('2017-03-03'));

        $this->entityManager->getRepository(Game::class)->save($game, true);

        return $this->render('game/index.html.twig', [
            'controller_name' => 'GameController'
        ]);
    }

    #[Route('/game/show/{id}', name: 'app_game_show')]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game
        ]);
    }

    #[Route('/games', name: 'app_game_list')]
    public function list(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll();

        return $this->render('game/list.html.twig', [
            'games' => $games
        ]);
    }

    #[Route('/games/top', name: 'app_game_top_list')]
    public function top(GameRepository $gameRepository): Response
    {
        $score = 10;
        //$games = $gameRepository->findAllEqualThanScore($score);
        $games = $gameRepository->findAllEqualThanScoreDql($score);

        return $this->render('game/top_list.html.twig', [
            'games' => $games
        ]);
    }

    #[Route('/game/edit/{id}', name: 'app_game_edit')]
    public function update(GameRepository $gameRepository, int $id): Response
    {
        $game = $gameRepository->find($id);

        if(!$game) {
            throw $this->createNotFoundException(
                'No game found id: '. $id
            );
        }

        $score = rand(1,10);
        $game->setScore($score);

        $gameRepository->save($game, true);

        return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
    }

    #[Route('/game/delete/{id}', name: 'app_game_delete')]
    public function delete(GameRepository $gameRepository, int $id): Response
    {
        $game = $gameRepository->find($id);

        if(!$game) {
            throw $this->createNotFoundException(
                'No game found id: '. $id
            );
        }

        $gameRepository->remove($game, true);

        return $this->redirectToRoute('app_game_list');
    }
}
