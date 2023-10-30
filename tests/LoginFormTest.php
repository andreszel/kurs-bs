<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormTest extends WebTestCase
{
    private $email = 'add@as.pl';
    private $password = 'test1234';

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

    public function test_can_see_login_form()
    {
        $client = static::createClient();
        $client->request('GET', '/login');

        $response = $client->getResponse();

        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200, $response->getStatusCode());
    }
}
