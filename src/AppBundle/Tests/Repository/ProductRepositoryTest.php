<?php
/**
 * Date: 02.02.16
 * Time: 17:46
 */

namespace AppBundle\Tests\Repository;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;

class ProductRepositoryTest extends AbstractWebCaseTest
{
    public function testByCategory()
    {
        /** @var EntityManager $em */
        $em = $this->docrine->getManager();
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name ' . $i)
                ->setSlug("test_name_$i")
                ->setCategory($category);
            $em->persist($product);
        }
        $product = new Product();
        $product->setName('not in category ' . $i)
            ->setSlug("not_in_category_$i");
        $em->persist($product);

        $otherCategory = new Category();
        $otherCategory->setName('other category')
            ->setSlug('other_category');
        $em->persist($otherCategory);

        $otherProduct = new Product();
        $otherProduct->setName('other product')
            ->setSlug('other_product')
            ->setCategory($otherCategory);
        $em->persist($otherProduct);
        $em->flush();

        $query = $em->getRepository('AppBundle:Product')->findByCategoryQuery($category);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(10, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('test name ', $product->getName());
        }

        $query = $em->getRepository('AppBundle:Product')->findByCategoryQuery($otherCategory);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(1, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('other product', $product->getName());
            $this->assertNotContains('test name ', $product->getName());
        }
    }

    public function testSimilar()
    {
        //Создадим две тестовых категории
        /** @var EntityManager $em */
        $em = $this->docrine->getManager();
        $category = (new Category())->setSlug('first_category')->setName('first category');
        $em->persist($category);
        $categorySecond = (new Category())->setSlug('second_category')->setName('second category');
        $em->persist($categorySecond);

        //Создадим основной товар
        $product = (new Product())->setName('original product')->setIsEnabled(true)->setSlug("originnal_product")->setMinPrice(10)->setCategory($category);
        $em->persist($product);
        $em->flush();
        //Создадим 1 похожий и 2 не похожих
        $product1 = (new Product())->setName('first similar')->setIsEnabled(true)->setSlug("first_similar")->setMinPrice(8.2)->setCategory($category);
        $product2 = (new Product())->setName('first not similar')->setIsEnabled(true)->setSlug("first_not_similar")->setMinPrice(6)->setCategory($category);
        $product3 = (new Product())->setName('second not similar')->setIsEnabled(true)->setSlug("second_not_similar")->setMinPrice(10)->setCategory($categorySecond);
        $em->persist($product1);
        $em->persist($product2);
        $em->persist($product3);
        $em->flush();

        //Проверим что видно только 1
        $products = $em->getRepository('AppBundle:Product')->findSimilar($product);
        $this->assertCount(1, $products);
        $this->assertEquals('first_similar', $products[0]->getSlug());

        //Создадим еще 4 похожих товара
        $product4 = (new Product())->setName('4 similar')->setIsEnabled(true)->setSlug("4_similar")->setMinPrice(8.2)->setCategory($category);
        $product5 = (new Product())->setName('5 similar')->setIsEnabled(true)->setSlug("5_similar")->setMinPrice(11.8)->setCategory($category);
        $product6 = (new Product())->setName('6 similar')->setIsEnabled(true)->setSlug("6_similar")->setMinPrice(10)->setCategory($category);
        $em->persist($product4);
        $em->persist($product5);
        $em->persist($product6);
        $em->flush();

        //проверим что показалось только 3
        $products = $em->getRepository('AppBundle:Product')->findSimilar($product);
        $this->assertCount(3, $products);
        $this->assertNotContains($product2, $products);
        $this->assertNotContains($product3, $products);
    }

    public function testNew()
    {
        /** @var EntityManager $em */
        $em = $this->docrine->getManager();
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        $product = null;
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name ' . $i)
                ->setIsEnabled(true)
                ->setEnabledAt((new \DateTime())->modify("+$i day"))
                ->setSlug("test_name_$i")
                ->setCategory($category);
            $em->persist($product);
        }
        $em->flush();
        $products = $em->getRepository('AppBundle:Product')->findNew();
        $this->assertCount(6, $products);
        $this->assertNotNull($product);
        $this->assertEquals('test_name_9', $products[0]->getSlug());
        $this->assertEquals('test_name_4', $products[5]->getSlug());
        foreach ($products as $product) {
            $this->assertTrue($product->getIsEnabled());
        }
    }

    public function testPopular()
    {
        /** @var EntityManager $em */
        $em = $this->docrine->getManager();
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        $product = null;
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name ' . $i)
                ->setIsEnabled(true)
                ->setEnabledAt((new \DateTime())->modify("+$i day"))
                ->setVisitsCount(1000 - $i)
                ->setSlug("test_name_$i")
                ->setCategory($category);
            $em->persist($product);
        }
        $em->flush();
        $products = $em->getRepository('AppBundle:Product')->findPopular();
        $this->assertCount(6, $products);
        $this->assertNotNull($product);
        $this->assertEquals('test_name_0', $products[0]->getSlug());
        $this->assertEquals('test_name_5', $products[5]->getSlug());
        $previousProduct = $products[0];
        foreach ($products as $product) {
            $this->assertTrue($product->getIsEnabled());
            $this->assertLessThanOrEqual($previousProduct->getVisitsCount(), $product->getVisitsCount());
            $previousProduct = $product;
        }
    }
} 