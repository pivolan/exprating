<?php
/**
 * Date: 02.02.16
 * Time: 17:46
 */

namespace AppBundle\Tests\Repository;


use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;

class CategoryRepositoryTest extends AbstractWebCaseTest
{
    public function testChildrenQuery()
    {
        /**
         * @var EntityManager $em
         * @var Category $category
         * @var Category $category1
         * @var Category $category11
         * @var Product $product
         */
        list($em, $category, $category1, $category11, $product) = $this->fixture();

        $children = $em->getRepository('AppBundle:Category')->getChildren($category, false, null, 'ASC', true);
        $this->assertContains($category11, $children);
        $this->assertContains($category1, $children);
        $this->assertContains($category, $children);
        $products = $em->getRepository('AppBundle:Category')->getProductsRecursiveQuery($category)->getResult();
        $this->assertContains($product, $products);
    }

    public function testChildrenScalar()
    {
        /**
         * @var EntityManager $em
         * @var Category $category
         * @var Category $category1
         * @var Category $category11
         * @var Product $product
         */
        list($em, $category, $category1, $category11, $product) = $this->fixture();
        $childrenIds = $em->getRepository('AppBundle:Category')->getChildrenIds($category);
        $this->assertContains($category->getSlug(), $childrenIds);
        $this->assertContains($category1->getSlug(), $childrenIds);
        $this->assertContains($category11->getSlug(), $childrenIds);
    }

    /**
     * @return array
     */
    protected function fixture()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $category = (new Category())->setSlug('root_category')->setName('root category');
        $em->persist($category);
        $category1 = (new Category())->setSlug('category1')->setName('category1')->setParent($category);
        $em->persist($category1);
        $category11 = (new Category())->setSlug('second11')->setName('category11')->setParent($category1);
        $em->persist($category11);
        $product = (new Product())->setName('product')->setSlug('product')->setCategory($category11);
        $em->persist($product);
        $product2 = (new Product())->setName('product2')->setSlug('product2')->setCategory($category1);
        $em->persist($product2);
        $em->flush();
        return [$em, $category, $category1, $category11, $product];
    }
} 