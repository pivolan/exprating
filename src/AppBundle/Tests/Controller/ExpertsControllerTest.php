<?php

namespace Tests\AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpertsControllerTest extends WebTestCase
{
    public function testBreadCrumbsEdit()
    {
        $client = static::createClient();

        $crawler = $client->request(
            'GET',
            '/expert/edit/admin',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Мой профиль', $crawler->filter('.breadcrumbs')->text());
        $crawler = $client->request(
            'GET',
            '/expert/edit/curator',
            [],
            [],
            [
                'PHP_AUTH_USER' => 'admin',
                'PHP_AUTH_PW'   => 'qwerty',
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());
        $this->assertContains('Профиль эксперта', $crawler->filter('.breadcrumbs')->text());
    }
}
