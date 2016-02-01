<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Product;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadCategoryData extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $categories_names = ["Бытовая электроника",
                             "Электроника",
                             "Косметика, парфюмерия",
                             "Товары для детей",
                             "Спортинвентарь",
                             "Мебель интерьер",
        ];
        $categoriesTree = ["Электроника"           => ['Электроника и фото ',
                                                       'Телефоны и аксессуары ',
                                                       'Компьютеры'],
                           "Косметика, парфюмерия" => ['Средства по уходу за кожей и волосами ',
                                                       'Декоративная косметика ',
                                                       'Парфюмерия',
                                                       'Средства гигиены'],
                           "Мебель интерьер"       => ['Мебель для малышей ',
                                                       'Готовые комплекты ',
                                                       'Компьютерные столы, стулья и кресла',
                                                       'Корпусная мебель',
                                                       'Матрасы',
                                                       'Мягкая мебель',
                                                       'Надувная мебель',
                                                       'Основания для матрасов и наматрасникиь',
                                                       'Плетеная мебель',
                                                       'Раскладушки',
                                                       'Специальная мебель',
                                                       'Фурнитура',
                                                       'Постеры, картины',
                                                       'Товары для дачи',
                                                       'Товары для дома и ремонта',],
        ];
        foreach ($categories_names as $name) {
            $category = new Category();
            $category->setName($name);
            $manager->persist($category);
            if (isset($categoriesTree[$name])) {
                foreach ($categoriesTree[$name] as $childName) {
                    $childCategory = new Category();
                    $childCategory->setParent($category)
                        ->setName($childName);
                    $manager->persist($childCategory);
                }
            }
        }
        $manager->flush();
    }
}