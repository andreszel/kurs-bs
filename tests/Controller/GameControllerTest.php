<?php

namespace App\Tests\Controller;

use App\Entity\Category;
use App\Entity\Game;
use App\Repository\CategoryRepository;
use App\Repository\GameRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameControllerTest extends WebTestCase
{
    private $emailTestUserWithRoleAdd = 'add@as.pl';

    public function test_add_new_game_use_form_game(): void
    {
        $categoryName = 'Test category';
        // test game data
        $gameName = 'Fortnite';
        $gameDescription = 'Test description xxx - fortnite';
        $gameScore = 7;
        $gameReleaseDate = '2011-03-01';

        $client = static::createClient();
        $client->followRedirects();

        $userRepository = static::getContainer()->get(UserRepository::class);
        $categoryRepository = static::getContainer()->get(CategoryRepository::class);
        $gameRepository = static::getContainer()->get(GameRepository::class);

        $category = new Category();
        $category->setName($categoryName);
        $categoryRepository->save($category, true);

        // retrieve the test user
        $userEntity = $userRepository->findOneByEmail($this->emailTestUserWithRoleAdd);

        $client->loginUser($userEntity);
        $crawler = $client->request('GET', '/game/new');
        $authCrawlerNode = $crawler->selectButton('Save');

        $form = $authCrawlerNode->form([
            'game[name]' => $gameName,
            'game[description]' => $gameDescription,
            'game[score]' => $gameScore,
            'game[releaseDate]' => $gameReleaseDate,
            'game[category]' => $category->getId()
        ]);

        $crawler = $client->submit($form);
        $game = $gameRepository->findOneBy([
            'name' => $gameName,
            'score' => $gameScore
        ]);

        // asserts
        $this->assertNotNull($game);
        $this->assertInstanceOf(Game::class, $game);
        $this->assertEquals($gameName, $game->getName());
        $this->assertEquals($gameDescription, $game->getDescription());
        $this->assertEquals($gameScore, $game->getScore());
    }

    public function test_games_list_items(): void
    {
        $client = static::createClient();
        $client->followRedirects();

        // retrieve the test user
        $userRepository = static::getContainer()->get(UserRepository::class);
        $userEntity = $userRepository->findOneByEmail($this->emailTestUserWithRoleAdd);

        $client->loginUser($userEntity);

        $crawler = $client->request('GET', '/games');

        $expectedCount = 20;
        
        $elements = $crawler->filter('li.game-item');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Games list');
        $this->assertCount($expectedCount, $elements, "Games list contains " . $expectedCount . " elements");
        $this->assertSame($expectedCount, $elements->count());
    }
}
