<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{
    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/product_10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('title 10', $crawler->filter('.content h1')->text());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rubric/elektronika/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Электроника', $crawler->filter('.content h1')->text());
        $this->assertContains('rel="next"', $crawler->filter('ul.pagination')->html());
        $this->assertContains('rel="prev"', $crawler->filter('ul.pagination')->html());
    }
}
