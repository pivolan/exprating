<?php

/**
 * Date: 02.02.16
 * Time: 17:46.
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Dto\CategoryJsTree;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;

class CategoryRepositoryTest extends AbstractWebCaseTest
{
    public function testChildrenQuery()
    {
        /**
         * @var EntityManager
         * @var Category
         * @var Category
         * @var Category
         * @var Product
         */
        list($em, $category, $category1, $category11, $product, $product2) = $this->fixture();

        $children = $em->getRepository('AppBundle:Category')->getChildren($category, false, null, 'ASC', true);
        $this->assertContains($category11, $children);
        $this->assertContains($category1, $children);
        $this->assertContains($category, $children);
        $products = $em->getRepository('AppBundle:Category')->getProductsRecursiveQuery($category)->getResult();
        $this->assertContains($product, $products);
        $this->assertContains($product2, $products);
    }

    public function testChildrenScalar()
    {
        /**
         * @var EntityManager
         * @var Category
         * @var Category
         * @var Category
         * @var Product
         */
        list($em, $category, $category1, $category11) = $this->fixture();
        $childrenIds = $em->getRepository('AppBundle:Category')->getChildrenIds($category);
        $this->assertContains($category->getSlug(), $childrenIds);
        $this->assertContains($category1->getSlug(), $childrenIds);
        $this->assertContains($category11->getSlug(), $childrenIds);
    }

    public function testGetForJsTree()
    {
        $user = $this->client->getContainer()->get('exprating_faker.faker.fake_entities_generator')->user();
        $categorySlug = 'akkumulyatornye-batarei-4';
        $user->addCategory($this->em->getReference(Category::class, $categorySlug));
        $user->addAdminCategory($this->em->getReference(Category::class, $categorySlug));
        $this->em->persist($user);
        $this->em->flush();
        $categories = $this->em->getRepository('AppBundle:Category')->getForJsTree($user);
        $this->assertCount(1, $categories);
        $categoryJsTree = new CategoryJsTree();
        $categoryJsTree->name = 'Аккумуляторные батареи';
        $categoryJsTree->id = $categorySlug;
        $categoryJsTree->parent_id = 'avtozapchasti-2';
        $categoryJsTree->product_count = 15;

        $expectedResult = [
            $categorySlug => $categoryJsTree,
        ];
        $this->assertEquals(
            $expectedResult,
            $categories
        );
        $categories = $this->em->getRepository('AppBundle:Category')->getForJsTree(null, $user);
        $this->assertCount(1, $categories);
        $this->assertEquals(
            $expectedResult,
            $categories
        );
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

        return [$em, $category, $category1, $category11, $product, $product2];
    }
}
