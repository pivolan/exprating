<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Feedback;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\RatingSettings;
use AppBundle\Form\CommentType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadratingSettingsData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Category[] $categories */
        $categories = $manager->getRepository('AppBundle:Category')->findAll();
        foreach ($categories as $key => $category) {
            $ratingSettings = new RatingSettings();
            $ratingSettings->setRating1Label('Качество работы')
                ->setCategory($category)
                ->setRating2Label('Уровень шума')
                ->setRating3Label("Цена/Качество")
                ->setRating4Label("Отзывы");
            $manager->persist($ratingSettings);
        }
        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on
     *
     * @return array
     */
    public function getDependencies()
    {
        return ['AppBundle\DataFixtures\ORM\LoadCategoryData'];
    }
}