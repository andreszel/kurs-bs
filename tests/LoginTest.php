<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function test_login_user(): void
    {
        $client = static::createClient();
        
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('add@as.pl');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        $crawler = $client->request('GET', '/game/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h3', 'Formularz dodawania nowej gry');

        /* $history = $client->getHistory();
        $cookieJar = $client->getCookieJar();
        // the HttpKernel request instance
        $request = $client->getRequest();

        // the BrowserKit request instance
        $request = $client->getInternalRequest();

        // the HttpKernel response instance
        $response = $client->getResponse();

        // the BrowserKit response instance
        $response = $client->getInternalResponse();

        // the Crawler instance
        $crawler = $client->getCrawler(); */
    }
}
