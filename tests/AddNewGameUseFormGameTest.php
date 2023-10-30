<?php

namespace App\Tests;

use App\Entity\Category;
use App\Entity\Game;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AddNewGameUseFormGameTest extends WebTestCase
{
    public function test_add_new_game_use_form_game(): void
    {
        $emailTestUserWithRoleAdd = 'add@as.pl';
        $categoryName = 'Test category';
        // test game data
        $gameName = 'Fortnite';
        $gameDescription = 'Test description xxx - fortnite';
        $gameScore = 7;

        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $gameRepository = static::getContainer()->get(GameRepository::class);

        $category = new Category();
        $category->setName($categoryName);
        $categoryRepository->save($category, true);

        // retrieve the test user
        $userEntity = $userRepository->findOneByEmail($emailTestUserWithRoleAdd);

        $client->loginUser($userEntity);
        $crawler = $client->request('GET', '/game/new');
        $authCrawlerNode = $crawler->selectButton('Save');

        $form = $authCrawlerNode->form([
            'game[name]' => $gameName,
            'game[description]' => $gameDescription,
            'game[score]' => $gameScore,
            'game[releaseDate]' => '2011-03-01',
            'game[category]' => $category->getId()
        ]);

        $crawler = $client->submit($form);
        $game = $gameRepository->findOneBy([
            'name' => $gameName,
            'score' => $gameScore
        ]);

        // asserts
        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals($gameName, $game->getName());
        $this->assertEquals($gameDescription, $game->getDescription());
        $this->assertEquals($gameScore, $game->getScore());
    }
}
