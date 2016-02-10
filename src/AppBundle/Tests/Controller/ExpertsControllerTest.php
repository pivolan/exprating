<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpertsControllerTest extends WebTestCase
{
    public function testList()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertContains('Admin Admin', $crawler->filter('div.expert-name > a')->text());
    }

    public function testDetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts/admin');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertEquals(5, $crawler->filter('li.comment-wrapper')->count());
    }

    public function testDetail404()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/experts/pwerijgerijnferjnerkl');
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testProfileAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile/expert');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());

        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));

        $crawler = $client->request('GET', '/profile/expert');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testCreateAction()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/profile/expert/create/elektronika');
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
        $client = static::createClient(array(), array(
            'PHP_AUTH_USER' => 'admin',
            'PHP_AUTH_PW'   => 'qwerty',
        ));
        $crawler = $client->request('GET', '/profile/expert/create/elektronika');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $crawler = $client->request('GET', '/profile/expert/create/furnitura');
        $this->assertEquals(403, $client->getResponse()->getStatusCode());
    }
}
