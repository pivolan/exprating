<?php
/**
 * Date: 18.03.16
 * Time: 16:15
 */

namespace Exprating\ImportBundle\Tests\Repository;

use Doctrine\ORM\AbstractQuery;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AliasItemRepositoryTest extends WebTestCase
{
    public function testFindAll()
    {
        $client = static::createClient();
        $emImport = $client->getContainer()->get('doctrine.orm.import_entity_manager');
        $aliasItems = $emImport->getRepository('ExpratingImportBundle:AliasItem')->findBy([], null, 1);
        $this->assertNotNull($aliasItems);
    }
}
