<?php

namespace AppBundle\Tests\Controller;

use AppBundle\Tests\AbstractWebCaseTest;

class ProductControllerTest extends AbstractWebCaseTest
{
    public function testShow()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/product/product_10');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('title 10', $crawler->filter('.content h1')->text());
    }

    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/rubric/elektronika');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Электроника', $crawler->filter('.content h1')->text());
    }
}
