<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\User;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCategoryData extends AbstractFixture implements
    FixtureInterface,
    ContainerAwareInterface,
    DependentFixtureInterface
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
        $categoriesTree = json_decode(file_get_contents(__DIR__.'/categories.json'), true);
        /** @var Slugify $slugify */
        $slugify = $this->container->get('appbundle.slugify');
        $curator = $this->getReference(LoadUserData::REFERENCE_CURATOR_USER);
        $expert = $this->getReference(LoadUserData::REFERENCE_EXPERT_USER);
        /** @var User $categoryAdmin */
        $categoryAdmin = $this->getReference(LoadUserData::REFERENCE_CATEGORY_ADMIN_USER);
        $rootCategory = (new Category())->setSlug(Category::ROOT_SLUG)->setName(Category::ROOT_SLUG);
        $manager->persist($rootCategory);
        $count = 0;
        foreach ($categoriesTree as $key => $categoryLevel1) {
            $count++;
            $name = $categoryLevel1['name'];
            $category = (new Category())
                ->setName($name)
                ->setParent($rootCategory)
                ->setSlug($slugify->slugify($name));
            $manager->persist($category);
            $curator->addCategory($category);
            $expert->addCategory($category);
            $categoryAdmin->addCategory($category);
            foreach ($categoryLevel1['children'] as $key2 => $categoryLevel2) {
                $count++;
                $childName = $categoryLevel2['name'];
                $childCategory = (new Category())
                    ->setParent($category)
                    ->setName($childName)
                    ->setSlug($slugify->slugify($childName).'-'.$count);
                $manager->persist($childCategory);
                $categoryAdmin->addAdminCategory($childCategory);
                $curator->addCategory($childCategory);
                $expert->addCategory($childCategory);
                $categoryAdmin->addCategory($childCategory);
                foreach ($categoryLevel2['children'] as $key3 => $categoryLevel3) {
                    $count++;
                    $name = $categoryLevel3['name'];
                    $childChildCategory = (new Category())
                        ->setName($name)
                        ->setSlug($slugify->slugify($name).'-'.$count)
                        ->setParent($childCategory);
                    $manager->persist($childChildCategory);
                    $curator->addCategory($childChildCategory);
                    $expert->addCategory($childChildCategory);
                    $categoryAdmin->addCategory($childChildCategory);
                    $categoryAdmin->addAdminCategory($childChildCategory);
                }
            }
        }
        $curator->addCategory($rootCategory);
        $expert->addCategory($rootCategory);
        $categoryAdmin->addCategory($rootCategory);
        $categoryAdmin->addAdminCategory($rootCategory);
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
        return [LoadUserData::class];
    }
}
