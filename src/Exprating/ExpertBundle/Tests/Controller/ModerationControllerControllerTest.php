<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ModerationControllerControllerTest extends WebTestCase
{
    public function testWaitlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/moderate/wait-list');
    }

    public function testProductdetail()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/moderate/product/{slug}');
    }

}
