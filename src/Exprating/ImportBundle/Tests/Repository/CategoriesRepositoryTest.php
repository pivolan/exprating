<?php
/**
 * Date: 18.03.16
 * Time: 16:15
 */

namespace Exprating\ImportBundle\Tests\Repository;

use Doctrine\ORM\AbstractQuery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoriesRepositoryTest extends WebTestCase
{
    public function testGetAllQuery()
    {
        $client = static::createClient();
        $emImport = $client->getContainer()->get('doctrine.orm.import_entity_manager');
        $categories = $emImport->getRepository('ExpratingImportBundle:Categories')->getFreeLastLevel();
        $this->assertNotNull($categories);
    }
}
