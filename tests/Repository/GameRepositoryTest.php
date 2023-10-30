<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRepositoryTest extends KernelTestCase
{
    public function test_save_new_game_to_database(): void
    {
        $kernel = self::bootKernel();
        $container = self::getContainer();

        $gameName = 'Test assasin';
        $gameDescription = 'Test description - lorem ipsum';
        $gameScore = 7;
        $gameReleaseDate = new \DateTime('2007-11-13');

        $entityManager = $container->get(EntityManagerInterface::class);

        $game = new Game();

        $game->setName($gameName)
            ->setDescription($gameDescription)
            ->setScore($gameScore)
            ->setReleaseDate($gameReleaseDate);
        
        $gameRepository = $entityManager->getRepository(Game::class);

        $gameRepository->save($game, true);
        
        $game = $gameRepository->find($game->getId());

        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals($gameName, $game->getName());
        $this->assertEquals($gameDescription, $game->getDescription());
        $this->assertEquals($gameScore, $game->getScore());
    }
}
