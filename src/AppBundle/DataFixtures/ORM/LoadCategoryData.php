<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\PeopleGroup;
use AppBundle\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategoryData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface, DependentFixtureInterface
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
        $categories_names = ['Бытовая электроника',
                             'Электроника',
                             'Косметика, парфюмерия',
                             'Товары для детей',
                             'Спортинвентарь',
                             'Мебель интерьер',
        ];
        $categoriesTree = ['Электроника' => ['Электроника и фото ',
                                                       'Телефоны и аксессуары ',
                                                       'Компьютеры', ],
                           'Косметика, парфюмерия' => ['Средства по уходу за кожей и волосами ',
                                                       'Декоративная косметика ',
                                                       'Парфюмерия',
                                                       'Средства гигиены', ],
                           'Мебель интерьер' => ['Мебель для малышей ',
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
        /** @var Slugify $slugify */
        $slugify = $this->container->get('appbundle.slugify');
        /** @var User $admin */
        $admin = $this->getReference(LoadUserData::REFERENCE_ADMIN_USER);
        $curator = $this->getReference(LoadUserData::REFERENCE_CURATOR_USER);
        $expert = $this->getReference(LoadUserData::REFERENCE_EXPERT_USER);
        /** @var User $categoryAdmin */
        $categoryAdmin = $this->getReference(LoadUserData::REFERENCE_CATEGORY_ADMIN_USER);
        /** @var PeopleGroup $peopleGroup */
        $peopleGroup = $this->getReference(LoadPeopleGroupData::DLYA_VSEH);
        foreach ($categories_names as $key => $name) {
            $category = new Category();
            $category->setName($name);
            $category->setSlug($slugify->slugify($name));
            $category->addPeopleGroup($peopleGroup);
            $manager->persist($category);
            if (isset($categoriesTree[$name])) {
                foreach ($categoriesTree[$name] as $childName) {
                    $childCategory = new Category();
                    $childCategory->setParent($category)
                        ->setName($childName);
                    $childCategory->setSlug($slugify->slugify($childName));
                    $childCategory->addPeopleGroup($peopleGroup);
                    $manager->persist($childCategory);
                    $categoryAdmin->addAdminCategory($childCategory);
                }
            }
            $admin->addCategory($category);
            $curator->addCategory($category);
            $expert->addCategory($category);
            $categoryAdmin->addCategory($category);
            $categoryAdmin->addAdminCategory($category);
            $this->addReference("category_$key", $category);
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
        return [LoadUserData::class, LoadPeopleGroupData::class];
    }
}
