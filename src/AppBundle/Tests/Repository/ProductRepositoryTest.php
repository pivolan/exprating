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

        $query = $em->getRepository('AppBundle:Product')->findByCategory($category);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(10, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('test name ', $product->getName());
        }

        $query = $em->getRepository('AppBundle:Product')->findByCategory($otherCategory);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(1, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('other product', $product->getName());
            $this->assertNotContains('test name ', $product->getName());
        }

    }
} 