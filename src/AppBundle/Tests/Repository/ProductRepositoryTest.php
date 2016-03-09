<?php

/**
 * Date: 02.02.16
 * Time: 17:46.
 */

namespace AppBundle\Tests\Repository;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use AppBundle\ProductFilter\ProductFilter;
use AppBundle\Tests\AbstractWebCaseTest;
use Doctrine\ORM\EntityManager;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CharacteristicSearchParameter;
use Exprating\CharacteristicBundle\CharacteristicSearchParam\CommonProductSearch;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;

class ProductRepositoryTest extends AbstractWebCaseTest
{
    public function testFindByFilterQuery()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $peopleGrouop = new PeopleGroup();
        $peopleGrouop->setSlug('test')->setName('test_name');
        $em->persist($peopleGrouop);
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category')
            ->addPeopleGroup($peopleGrouop);
        $em->persist($category);
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name '.$i)
                ->setSlug("test_name_$i")
                ->setIsEnabled(true)
                ->setRating($i)
                ->setCategory($category)
                ->addPeopleGroup($peopleGrouop);
            $em->persist($product);
        }
        $product = new Product();
        $product->setName('not in category '.$i)
            ->setIsEnabled(true)
            ->setSlug("not_in_category_$i");
        $em->persist($product);

        $otherCategory = new Category();
        $otherCategory->setName('other category')
            ->setSlug('other_category');
        $em->persist($otherCategory);

        $otherProduct = new Product();
        $otherProduct->setName('other product')
            ->setIsEnabled(true)
            ->setSlug('other_product')
            ->setCategory($otherCategory);
        $em->persist($otherProduct);
        $em->flush();
        $productFilter = new ProductFilter();
        $productFilter->setCategory($category);
        $query = $em->getRepository('AppBundle:Product')->findByFilterQuery($productFilter);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(10, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('test name ', $product->getName());
        }
        $productFilter->setCategory($otherCategory);
        $query = $em->getRepository('AppBundle:Product')->findByFilterQuery($productFilter);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(1, count($products));
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertContains('other product', $product->getName());
            $this->assertNotContains('test name ', $product->getName());
        }
        //productFilter test
        $productFilter = new ProductFilter();
        $productFilter->setSortDirection(ProductFilter::DIRECTION_ASC)->setSortField(ProductFilter::FIELD_RATING)
            ->setCategory($category);
        $query = $em->getRepository('AppBundle:Product')->findByFilterQuery($productFilter);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(10, count($products));
        $prevProduct = $products[0];
        foreach ($products as $product) {
            $this->assertInstanceOf('\AppBundle\Entity\Product', $product);
            $this->assertGreaterThanOrEqual($prevProduct->getRating(), $product->getRating());
            $prevProduct = $product;
        }
        $productFilter->setPeopleGroup($peopleGrouop);
        $query = $em->getRepository('AppBundle:Product')->findByFilterQuery($productFilter);
        /** @var Product[] $products */
        $products = $query->getResult();
        $this->assertEquals(10, count($products));
    }

    public function testFindSimilar()
    {
        //Создадим две тестовых категории
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $category = (new Category())->setSlug('first_category')->setName('first category');
        $em->persist($category);
        $categorySecond = (new Category())->setSlug('second_category')->setName('second category');
        $em->persist($categorySecond);

        //Создадим основной товар
        $product = (new Product())->setName('original product')->setIsEnabled(true)->setSlug(
            'originnal_product'
        )->setMinPrice(10)->setCategory($category);
        $em->persist($product);
        $em->flush();
        //Создадим 1 похожий и 2 не похожих
        $product1 = (new Product())->setName('first similar')->setIsEnabled(true)->setSlug(
            'first_similar'
        )->setMinPrice(8.2)->setCategory($category);
        $product2 = (new Product())->setName('first not similar')->setIsEnabled(true)->setSlug(
            'first_not_similar'
        )->setMinPrice(6)->setCategory($category);
        $product3 = (new Product())->setName('second not similar')->setIsEnabled(true)->setSlug(
            'second_not_similar'
        )->setMinPrice(10)->setCategory($categorySecond);
        $em->persist($product1);
        $em->persist($product2);
        $em->persist($product3);
        $em->flush();

        //Проверим что видно только 1
        $products = $em->getRepository('AppBundle:Product')->findSimilar($product);
        $this->assertCount(1, $products);
        $this->assertEquals('first_similar', $products[0]->getSlug());

        //Создадим еще 4 похожих товара
        $product4 = (new Product())->setName('4 similar')->setIsEnabled(true)->setSlug('4_similar')->setMinPrice(
            8.2
        )->setCategory($category);
        $product5 = (new Product())->setName('5 similar')->setIsEnabled(true)->setSlug('5_similar')->setMinPrice(
            11.8
        )->setCategory($category);
        $product6 = (new Product())->setName('6 similar')->setIsEnabled(true)->setSlug('6_similar')->setMinPrice(
            10
        )->setCategory($category);
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

    public function testFindNew()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        $product = null;
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name '.$i)
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

    public function testFindPopular()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        $product = null;
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name '.$i)
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

    public function testFindByExpertQuery()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();
        $expert = new User();
        $expert->setUsername('expert1')
            ->setUsernameCanonical('expert1')
            ->setEmail('expert1@exprating.lo')
            ->setEmailCanonical('expert1@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setEnabled(true)
            ->addRole(User::ROLE_EXPERT);

        $em->persist($expert);

        $category = new Category();
        $category->setSlug('test_category')
            ->setName('test category');
        $em->persist($category);
        $product = null;
        for ($i = 0; $i < 10; $i++) {
            $product = new Product();
            $product->setName('test name '.$i)
                ->setIsEnabled(false)
                ->setSlug("test_name_$i")
                ->setCategory($category);
            if ($i % 2 == 0) {
                $product->setExpertUser($expert);
            }
            if ($i % 4 == 0) {
                $product->setIsEnabled(true);
            }
            $em->persist($product);
        }
        $em->flush();
        /** @var Product[] $products */
        $products = $em->getRepository('AppBundle:Product')->findByExpertPublishedQuery($expert)->getResult();
        $this->assertCount(3, $products);
        foreach ($products as $product) {
            $this->assertTrue($product->getIsEnabled());
            $this->assertEquals($expert, $product->getExpertUser());
        }
    }

    public function testFindByCharacteristicsQuery()
    {
        /** @var EntityManager $em */
        $em = $this->doctrine->getManager();

        //Создадим товар с одной характеристикой, создадим критерий и найдем этот товар.
        //Создадим критерий чтобы не найти этот товар.
        //Проверим граничные условия
        $category = new Category();
        $category->setName('category_name')->setSlug('category_slug');
        $em->persist($category);

        $product = new Product();
        $product->setName('фиолетовый носок')
            ->setSlug('test_char_product')
            ->setMinPrice(10000.25)
            ->setCategory($category);
        $em->persist($product);
        $characteristic = new Characteristic();
        $characteristic->setName('size')
            ->setSlug('size_foot')
            ->setLabel('size_foot')
            ->setType(Characteristic::TYPE_INT);
        $em->persist($characteristic);

        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setProduct($product)
            ->setCharacteristic($characteristic)
            ->setValue(25);
        $em->persist($productCharacteristic);

        $em->flush();
        //Проверяем что размер больше 20, должно найти
        $criteria = new CharacteristicSearchParameter();
        $criteria->setName($characteristic->getSlug())
            ->setType($characteristic->getType())
            ->setValueGTE(20);
        $commonCriteria = new CommonProductSearch();
        $commonCriteria->addCharacteristics($criteria);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertContains($product, $products);

        //Проверяем что размер больше 20 и меньше 30
        $criteria = new CharacteristicSearchParameter();
        $criteria->setName($characteristic->getSlug())
            ->setType($characteristic->getType())
            ->setValueGTE(20)
            ->setValueLTE(30);
        $commonCriteria = new CommonProductSearch();
        $commonCriteria->addCharacteristics($criteria);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertContains($product, $products);
        //Добавим условие по цене
        $commonCriteria = new CommonProductSearch();
        $commonCriteria->setPriceGTE(10000.25)
            ->setPriceLTE(10000.25)
            ->addCharacteristics($criteria);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertContains($product, $products);
        //Не найдем
        $criteria->setValueGTE(26);
        $commonCriteria = new CommonProductSearch();
        $commonCriteria->setPriceGTE(10000.25)
            ->setPriceLTE(10000.25)
            ->addCharacteristics($criteria);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertNotContains($product, $products);

        $criteria->setValueGTE(20);
        $commonCriteria = new CommonProductSearch();
        $commonCriteria->setPriceGTE(10010)
            ->addCharacteristics($criteria);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertNotContains($product, $products);

        //Создадим товар с несколькими характеристиками, найдем его по одной характеристике, по 2м, по всем.
        //Не найдем его по 1 характеристике, не найдем все характеристики верные и одна не верная.
        $characteristic = new Characteristic();
        $characteristic->setName('weight_test')
            ->setSlug('weight_test')
            ->setLabel('weight_test')
            ->setType(Characteristic::TYPE_DECIMAL);
        $em->persist($characteristic);

        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setProduct($product)
            ->setCharacteristic($characteristic)
            ->setValue(100.12);
        $em->persist($productCharacteristic);
        $em->flush();

        $characteristic = new Characteristic();
        $characteristic->setName('options')
            ->setSlug('options')
            ->setLabel('options')
            ->setType(Characteristic::TYPE_STRING);
        $em->persist($characteristic);

        $productCharacteristic = new ProductCharacteristic();
        $productCharacteristic->setProduct($product)
            ->setCharacteristic($characteristic)
            ->setValue('Больше гантели красные');
        $em->persist($productCharacteristic);
        $em->flush();
        //Ищем по трем критериям
        $criteria = (new CharacteristicSearchParameter())
            ->setName('size_foot')
            ->setType(Characteristic::TYPE_INT)
            ->setValueGTE(20)
            ->setValueLTE(30);
        $criteria2 = (new CharacteristicSearchParameter())
            ->setName('weight_test')
            ->setType(Characteristic::TYPE_DECIMAL)
            ->setValueGTE(100)
            ->setValueLTE(101);
        $criteria3 = (new CharacteristicSearchParameter())
            ->setName('options')
            ->setType(Characteristic::TYPE_STRING)
            ->setValue('Гантели');

        $commonCriteria = new CommonProductSearch();
        $commonCriteria->setPriceGTE(9999)
            ->setPriceLTE(10001)
            ->setName('фиолетов')
            ->addCharacteristics($criteria)
            ->addCharacteristics($criteria2)
            ->addCharacteristics($criteria3);

        $products = $em->getRepository('AppBundle:Product')->findByCharacteristicsQuery(
            $commonCriteria,
            $category
        )->getResult();
        $this->assertContains($product, $products);
    }
}
