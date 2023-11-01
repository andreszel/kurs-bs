<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    private $email = 'add@as.pl';
    private $password = 'test1234';

    public function test_can_see_login_form()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, $response->getStatusCode());
    }

    public function test_login_use_form_type_first(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $loginLink = $crawler->selectLink('Login')->link();

        $crawler = $client->click($loginLink);

        // select the button
        $authCrawlerNode = $crawler->selectButton('Zaloguj się');

        $form = $authCrawlerNode->form(array(
            'email' => $this->email,
            'password' => $this->password
        ));

        $crawler = $client->submit($form);

        $link = $crawler->selectLink('Games')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Games list');
    }

    public function test_login_use_form_type_second(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Zaloguj się', [
            'email'    => $this->email,
            'password' => $this->password,
        ]);

        $link = $crawler->selectLink('Games')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Games list');
    }

    public function test_login_use_form_type_threeth(): void
    {
        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();


        // select the button
        $authCrawlerNode = $crawler->selectButton('Zaloguj się');

        // retrieve the Form object for the form belonging to this button
        $form = $authCrawlerNode->form();

        // set values on a form object
        $form['email'] = $this->email;
        $form['password'] = $this->password;

        // submit the Form object
        $crawler = $client->submit($form);

        $link = $crawler->selectLink('Games')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Games list');
    }

    public function test_simulate_logged_user(): void
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
