<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function test_homepage(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h2', 'Home Page');
    }

    public function test_about(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('O nas')->link();

        $client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Witaj na naszej stronie o grach komputerowych!');
    }
}
