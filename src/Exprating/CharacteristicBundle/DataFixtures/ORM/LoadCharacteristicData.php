<?php

namespace Exprating\CharacteristicBundle\DataFixtures\ORM;

use AppBundle\DataFixtures\ORM\LoadCategoryData;
use AppBundle\DataFixtures\ORM\LoadProductData;
use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exprating\CharacteristicBundle\Entity\Characteristic;
use Exprating\CharacteristicBundle\Entity\ProductCharacteristic;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCharacteristicData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $keys = [
            'Тип пылесборника'                => [
                'Основные характеристики',
                null,
                Characteristic::TYPE_STRING,
                'контейнер',
            ],
            'Тип пылесоса'                    => [
                'Основные характеристики',
                null,
                Characteristic::TYPE_STRING,
                'обычный',
            ],
            'Тип уборки'                      => [
                'Основные характеристики',
                null,
                Characteristic::TYPE_STRING,
                'сухая',
            ],
            'Потребляемая мощность'           => ['Основные характеристики', 'Вт', Characteristic::TYPE_INT, 1800],
            'Мощность всасывания'             => ['Основные характеристики', 'Вт', Characteristic::TYPE_INT, 300],
            'Источник питания'                => ['Основные характеристики', null, Characteristic::TYPE_STRING, 'сеть'],
            'Дополнительные насадки'          => [
                'Насадки',
                null,
                Characteristic::TYPE_STRING,
                'пол/ковер; для твёрдого пола ProAnimal с мягкой щетиной; для мягкой мебели '.
                'ProAnimal; щелевая; для мягкой мебели со съемной щеткой ',
            ],
            'Турбощетка в комплекте'          => ['Насадки', null, Characteristic::TYPE_STRING, ' есть'],
            'Регулятор мощности (на корпусе)' => ['Дополнительно', null, Characteristic::TYPE_STRING, 'на корпусе'],
            'Фильтр тонкой очистки'           => ['Дополнительно', null, Characteristic::TYPE_STRING, 'есть'],
            'Конструкция трубы'               => [
                'Дополнительно',
                null,
                Characteristic::TYPE_STRING,
                'телескопическая',
            ],
            'Дополнительно'                   => [
                'Дополнительно',
                null,
                Characteristic::TYPE_STRING,
                'автосматывание сетевого шнура, ножной переключатель вкл./выкл. на корпусе, место для хранения насадок',
            ],
            'Вес'                             => ['Габариты', 'кг', Characteristic::TYPE_DECIMAL, 6.7],
        ];
        /** @var Slugify $slugify */
        $slugify = $this->container->get('appbundle.slugify');

        foreach ($keys as $key => $values) {
            $characteristic = new Characteristic();
            $characteristic->setName($key)
                ->setSlug($slugify->slugify($key))
                ->setLabel($key)
                ->setGroup($values[0])
                ->setType($values[2])
                ->setScale($values[1]);

            $manager->persist($characteristic);
        }
        $manager->flush();
        /** @var Product[] $products */
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        $characteristics = $manager->getRepository('CharacteristicBundle:Characteristic')->findAll();
        foreach ($products as $key => $product) {
            foreach ($characteristics as $characteristic) {
                $productCharacteristic = new ProductCharacteristic();
                $value = $keys[$characteristic->getName()][3];
                if (is_numeric($value) && $value == 300) {
                    $value = rand(100, 300);
                } elseif (is_numeric($value)) {
                    $value = rand(1200, 1800);
                }
                $productCharacteristic
                    ->setProduct($product)
                    ->setCharacteristic($characteristic)
                    ->setValue($value);

                $manager->persist($productCharacteristic);
            }
        }

        /** @var Category[] $categories */
        $categories = $manager->getRepository('AppBundle:Category')->findAll();
        foreach ($categories as $category) {
            foreach ($characteristics as $characteristic) {
                $category->addCharacteristic($characteristic);
            }
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array
     */
    public function getDependencies()
    {
        return [LoadProductData::class, LoadCategoryData::class];
    }
}
