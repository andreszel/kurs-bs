<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginFormTest extends WebTestCase
{
    public function test_login_use_form_type_first(): void
    {
        $email = 'add@as.pl';
        $password = 'test1234';

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();

        $loginLink = $crawler->selectLink('Login')->link();

        $crawler = $client->click($loginLink);

        // select the button
        $authCrawlerNode = $crawler->selectButton('Zaloguj się');
        //$form = $authCrawlerNode->form();

        /* // retrieve the Form object for the form belonging to this button
        $form = $authCrawlerNode->form();

        // set values on a form object
        $form['email'] = $email;
        $form['password'] = $password;

        // submit the Form object
        $client->submit($form); */

        /* $client->submit($form, [
            'email'    => $email,
            'password' => $password,
        ]); */

        /* $client->submitForm('Sign in', [
            'email'    => $email,
            'password' => $password,
        ]); */

        $form = $authCrawlerNode->form(array(
            'email' => $email,
            'password' => $password
        ));

        $crawler = $client->submit($form);

        $link = $crawler->selectLink('Games')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Games list');
    }

    public function test_login_use_form_type_second(): void
    {
        $email = 'add@as.pl';
        $password = 'test1234';

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();

        $client->submitForm('Zaloguj się', [
            'email'    => $email,
            'password' => $password,
        ]);

        $link = $crawler->selectLink('Games')->link();

        $client->click($link);

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertSelectorTextContains('h1', 'Games list');
    }

    public function test_login_use_form_type_threeth(): void
    {
        $email = 'add@as.pl';
        $password = 'test1234';

        $client = static::createClient();
        $client->followRedirects();
        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();


        // select the button
        $authCrawlerNode = $crawler->selectButton('Zaloguj się');

        // retrieve the Form object for the form belonging to this button
        $form = $authCrawlerNode->form();

        // set values on a form object
        $form['email'] = $email;
        $form['password'] = $password;

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
