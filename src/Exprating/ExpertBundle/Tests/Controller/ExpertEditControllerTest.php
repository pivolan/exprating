<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ExpertEditControllerTest extends WebTestCase
{
    public function testProductlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/edit-experts/product-list');
    }

    public function testProductdetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/edit-expert/product/{slug}');
    }
}
