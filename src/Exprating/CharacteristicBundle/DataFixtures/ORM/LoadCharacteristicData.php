<?php

namespace Exprating\CharacteristicBundle\DataFixtures\ORM;

use AppBundle\Entity\Feedback;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Exprating\CharacteristicBundle\Entity\Characteristic;
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

        $keys = ['Тип пылесборника'                => ['Основные характеристики', null, Characteristic::TYPE_STRING],
                 'Тип пылесоса'                    => ['Основные характеристики', null, Characteristic::TYPE_STRING],
                 'Тип уборки'                      => ['Основные характеристики', null, Characteristic::TYPE_STRING],
                 'Потребляемая мощность'           => ['Основные характеристики', 'Вт', Characteristic::TYPE_INT],
                 'Мощность всасывания'             => ['Основные характеристики', "Вт", Characteristic::TYPE_INT],
                 'Источник питания'                => ['Основные характеристики', null, Characteristic::TYPE_STRING],
                 'Дополнительные насадки'          => ['Насадки', null, Characteristic::TYPE_STRING],
                 'Турбощетка в комплекте'          => ['Насадки', null, Characteristic::TYPE_STRING],
                 'Регулятор мощности (на корпусе)' => ['Дополнительно', null, Characteristic::TYPE_STRING],
                 'Фильтр тонкой очистки'           => ['Дополнительно', null, Characteristic::TYPE_STRING],
                 'Конструкция трубы'               => ['Дополнительно', null, Characteristic::TYPE_STRING],
                 'Дополнительно'                   => ['Дополнительно', null, Characteristic::TYPE_STRING],
                 'Вес'                             => ['Габариты', 'кг', Characteristic::TYPE_DECIMAL],
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
        /** @var Product[] $products */
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $key => $product) {
            $commentsCount = ($key + 2) % 5;
            for ($i = 1; $i <= $commentsCount; $i++) {
                $comment = new \AppBundle\Entity\Comment();
                $comment->setProduct($product)
                    ->setFullName('Василий Зайцев ' . $key * $i)
                    ->setMessage('Хороший и надежный пылесос, пользуюсь им уже давно и ни разу не подводил');
                $manager->persist($comment);
            }
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadProductData', 'AppBundle\DataFixtures\ORM\LoadCategoryData',];
    }
}