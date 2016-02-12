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

        $this->assertContains('Published products', $client->getResponse()->getContent());
    }
    public function testUnpublishedProducts()
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ]);

        $crawler = $client->request('GET', '/expert/cabinet/unpublished-products');

        $this->assertContains('Unpublished products', $client->getResponse()->getContent());
    }
}
