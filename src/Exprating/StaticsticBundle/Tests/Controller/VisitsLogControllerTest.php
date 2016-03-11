<?php

namespace Exprating\StaticsticBundle\Tests\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitsLogControllerTest extends WebTestCase
{
    public function testProductAction()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $product = $em->getRepository('AppBundle:Product')->findOneBy(['slug'=>'product_1']);
        $count = count($em->getRepository('ExpratingStaticsticBundle:Visit')->findBy(
            ['product' => $product]
        ));
        $client->request('GET', '/log/visit/product/product_1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $countAfter = count($em->getRepository('ExpratingStaticsticBundle:Visit')->findBy(
            ['product' => $product]
        ));
        $this->assertEquals($countAfter -1, $count);
    }
}
