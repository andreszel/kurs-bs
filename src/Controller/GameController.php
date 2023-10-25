<?php

namespace App\Controller;

use App\Entity\Game;
use App\Form\GameType;
use App\Repository\GameRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GameController extends AbstractController
{
    public function __construct(private EntityManagerInterface $entityManager) {
    }

    #[Route('/games', name: 'app_game')]
    public function index(GameRepository $gameRepository): Response
    {
        $games = $gameRepository->findAll('id', 'DESC');

        return $this->render('game/index.html.twig', [
            'games' => $games
        ]);
    }

    #[Route('/game/new', name: 'app_game_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GameType::class, null, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();

            $entityManager->persist($game);
            $entityManager->flush();

            $this->addFlash('success', 'Successfull added!');

            return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
        }

        return $this->render('game/new.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/game/edit/{id}', name: 'app_game_edit')]
    public function update(Request $request, GameRepository $gameRepository, int $id): Response
    {
        $game = $gameRepository->find($id);

        if(!$game) {
            throw $this->createNotFoundException(
                'No game found id: '. $id
            );
        }

        $form = $this->createForm(GameType::class, $game, [
            'method' => 'POST'
        ]);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $game = $form->getData();

            $gameRepository->save($game, true);

            $this->addFlash('success', 'Successfull update!');

            return $this->redirectToRoute('app_game_show', ['id' => $game->getId()]);
        }

        return $this->render('game/edit.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/game/show/{id}', name: 'app_game_show')]
    public function show(Game $game): Response
    {
        return $this->render('game/show.html.twig', [
            'game' => $game
        ]);
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

        $this->addFlash('success', 'Successful delete!');

        return $this->redirectToRoute('app_game');
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

    #[Route('/random-games/{limit}', name: 'app_random_games')]
    public function randomGames(GameRepository $gameRepository, int $limit = 5): Response
    {
        $games = $gameRepository->findRandomGame($limit);

        return $this->render('game/random_games.html.twig', [
            'games' => $games
        ]);
    }
}
