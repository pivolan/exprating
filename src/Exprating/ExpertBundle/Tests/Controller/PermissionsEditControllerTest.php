<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PermissionsEditControllerTest extends WebTestCase
{
    public function testUserlist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/admin/user-list');
    }

    public function testUseredit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/admin/user-edit/{username}');
    }
}
