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

        $crawler = $client->request('GET', '/tovar/product_10/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('title_10', $crawler->filter('.content h1')->text());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rubric/organizacii/2/filter/minPrice/ASC/STATUS_ALL/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Организации - рейтинг по мнению экспертов', $crawler->filter('.content h1')->text());
        $this->assertContains('rel="next"', $crawler->filter('ul.pagination')->html());
        $this->assertContains('rel="prev"', $crawler->filter('ul.pagination')->html());
    }

    public function testSearch()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/tovar/search/1/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Найденные экспертные заключения', $crawler->filter('div.index-title')->text());
        $crawler = $client->request('GET', '/tovar/search/1/?search_params[string]=titl&search_btn=Найти');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Найденные экспертные заключения', $crawler->filter('div.index-title')->text());
    }
}
