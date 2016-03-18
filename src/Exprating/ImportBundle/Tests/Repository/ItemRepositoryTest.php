<?php
/**
 * Date: 18.03.16
 * Time: 16:15
 */

namespace Exprating\ImportBundle\Tests\Repository;

use Doctrine\ORM\AbstractQuery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ItemRepositoryTest extends WebTestCase
{
    public function testGetAllQuery()
    {
        $client = static::createClient();
        $emImport = $client->getContainer()->get('doctrine.orm.import_entity_manager');
        $query = $emImport->getRepository('ExpratingImportBundle:Item')->getAllQuery();
        $this->assertInstanceOf(AbstractQuery::class, $query);
    }
}
