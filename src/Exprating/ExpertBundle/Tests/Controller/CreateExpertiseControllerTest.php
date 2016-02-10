<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CreateExpertiseControllerTest extends WebTestCase
{
    public function testCategorylist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expert/cabinet/new-review/categories');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ]);

        $crawler = $client->request('GET', '/expert/cabinet/new-review/categories');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

    }

    public function testProductlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expert/cabinet/new-review/elektronika/products');
        $client = static::createClient();

        $crawler = $client->request('GET', '/expert/cabinet/new-review/elektronika/products');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ]);
        $crawler = $client->request('GET', '/expert/cabinet/new-review/elektronika/products');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $crawler = $client->request('GET', '/expert/cabinet/new-review/furnitura/products');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());

    }

    public function testCreatereview()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expert/cabinet/new-review/products/{slug}');
    }

    public function testComplete()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/expert/cabinet/new-review/complete');
    }
}
