<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpertsControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Admin Admin', $crawler->filter('div.expert-name > a')->text());
    }

    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts/expert');
        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertEquals(5, $crawler->filter('li.comment-wrapper')->count());
    }

    public function testDetail404()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts/pwerijgerijnferjnerkl');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }
}
