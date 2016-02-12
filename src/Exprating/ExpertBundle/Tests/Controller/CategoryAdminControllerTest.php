<?php

namespace Exprating\ExpertBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryAdminControllerTest extends WebTestCase
{
    public function testCategorylist()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/category-admin/categories');
    }

    public function testCategoryedit()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/cabinet/category-admin/edit/{slug}');
    }

}
