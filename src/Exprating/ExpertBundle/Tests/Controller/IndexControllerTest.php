<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testPublishedProducts()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ]);

        $crawler = $client->request('GET', '/expert/cabinet');

        $this->assertContains('Expert index page', $client->getResponse()->getContent());
    }
}
