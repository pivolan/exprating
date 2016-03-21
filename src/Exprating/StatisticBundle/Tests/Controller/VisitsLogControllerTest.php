<?php

namespace Exprating\StatisticBundle\Tests\Controller;

use AppBundle\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VisitsLogControllerTest extends WebTestCase
{
    public function testProductAction()
    {
        $client = static::createClient();

        $em = $client->getContainer()->get('doctrine.orm.default_entity_manager');
        $product = $em->getRepository('AppBundle:Product')->findOneBy(['slug' => 'product_1']);
        $count = count(
            $em->getRepository('ExpratingStatisticBundle:Visit')->findBy(
                ['product' => $product]
            )
        );
        $client->request('GET', '/log/visit/product/product_1');

        $this->assertEquals(200, $client->getResponse()->getStatusCode(), $client->getResponse()->getContent());

        $visits = $em->getRepository('ExpratingStatisticBundle:Visit')->findBy(
            ['product' => $product]
        );
        $countAfter = count($visits);
        $this->assertEquals($countAfter - 1, $count);
        $visit = $visits[0];
        $this->assertEquals('http://localhost/log/visit/product/product_1', $visit->getUrl());
        $this->assertNull($visit->getUser());
        $this->assertEquals($product, $visit->getProduct());
        $this->assertEquals($product->getExpertUser(), $visit->getExpert());
        $this->assertEquals($product->getExpertUser()->getCurator(), $visit->getCuratorFirstLevel());
        $this->assertEquals($product->getExpertUser()->getCurator()->getCurator(), $visit->getCuratorSecondLevel());
        $this->assertInstanceOf(\DateTime::class, $visit->getCreatedAt());
        $this->assertEquals('127.0.0.1', $visit->getIp());
        $this->assertEquals('Symfony2 BrowserKit', $visit->getUserAgent());
        $this->assertNotNull($visit->getId());
    }
}
