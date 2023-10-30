<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitOnAboutPageTest extends WebTestCase
{
    public function test_visit(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/');

        $link = $crawler->selectLink('O nas')->link();

        $client->click($link);

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('p', 'Witaj na naszej stronie o grach komputerowych!');
    }
}
