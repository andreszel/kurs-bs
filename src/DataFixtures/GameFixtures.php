<?php

namespace App\DataFixtures;

use App\Entity\Game;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class GameFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
            $game = new Game();

            $game->setName('Game ' . $i)
                ->setDescription('test description ' . $i)
                ->setScore(random_int(0,10))
                ->setReleaseDate(new \DateTime('now'));
            
                $manager->persist($game);
        }

        $manager->flush();
    }
}
