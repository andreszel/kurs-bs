<?php

namespace App\Tests\Repository;

use App\Entity\Game;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class GameRepositoryTest extends KernelTestCase
{
    public function test_save_new_game_in_database(): void
    {
        $kernel = self::bootKernel();
        $container = self::getContainer();

        $entityManager = $container->get(EntityManagerInterface::class);

        $game = new Game();

        $game->setName('Test assasin')
            ->setDescription('test description')
            ->setScore(8)
            ->setReleaseDate(new \DateTime('2007-11-13'));
        
        $gameRepository = $entityManager->getRepository(Game::class);

        $gameRepository->save($game, true);
        
        $game = $gameRepository->find($game->getId());

        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals('Test assasin', $game->getName());
        $this->assertEquals('test description', $game->getDescription());
        $this->assertEquals(8, $game->getScore());
    }
}
