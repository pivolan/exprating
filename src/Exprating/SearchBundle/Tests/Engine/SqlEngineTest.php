<?php

/**
 * Date: 08.02.16
 * Time: 21:44.
 */

namespace Exprating\SearchBundle\Tests\Engine;

use AppBundle\Entity\Product;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;

class SqlEngineTest extends AbstractWebCaseTest
{
    public function testSearch()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        //Создадим массив тестовых названий товаров
        $names = ['Red boots', 'blue boots', 'light table', 'yellow table', 'Yellow boots', 'Именно там Русское слово с english text'];
        foreach ($names as $name) {
            $product = (new Product())->setName($name)->setSlug($name)->setIsEnabled(true);
            $em->persist($product);
            $product2 = (new Product())->setName($name)->setSlug($name.'-2')->setIsEnabled(false);
            $em->persist($product2);
        }
        $em->flush();
        //Проверим по разным поисковым словам.
        $searchEngine = $this->client->getContainer()->get('search_bundle.sql');

        $products = $searchEngine->search('Red fire in our eyes');
        $this->assertCount(1, $products);
        $this->assertEquals('Red boots', $products[0]->getName());

        $products = $searchEngine->search('tables are not in here');
        $this->assertCount(0, $products);

        $products = $searchEngine->search('boots woman');
        $this->assertCount(3, $products);
        $this->assertEquals('Red boots', $products[0]->getName());
        $this->assertEquals('blue boots', $products[1]->getName());
        $this->assertEquals('Yellow boots', $products[2]->getName());

        $products = $searchEngine->search('fun YELLOW sun');
        $this->assertCount(2, $products);
        $this->assertEquals('yellow table', $products[0]->getName());
        $this->assertEquals('Yellow boots', $products[1]->getName());

        $products = $searchEngine->search('Русск YELL u eu sun');
        $this->assertCount(3, $products);
        $this->assertEquals('yellow table', $products[0]->getName());
        $this->assertEquals('Yellow boots', $products[1]->getName());
        $this->assertEquals('Именно там Русское слово с english text', $products[2]->getName());
    }
}
