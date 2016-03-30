<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class IndexControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains(
            'Каталог потребительских товаров: мнение и отзывы экспертов России',
            $crawler->filter('#container h1')->text()
        );
    }

    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tovar/product_10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('title_10', $crawler->filter('.content h1')->text());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rubric/avtozapchasti-2/2');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Автозапчасти - рейтинг по мнению экспертов', $crawler->filter('.content h1')->text());
        $this->assertContains('rel="prev"', $crawler->filter('ul.pagination')->html());
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tovar/search');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Найденные экспертные заключения', $crawler->filter('div.index-title')->text());
    }
}
