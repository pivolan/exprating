<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\Feedback;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Entity\RatingLabel;
use AppBundle\Form\CommentType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadRatingLabelData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Category[] $categories */
        $categories = $manager->getRepository('AppBundle:Category')->findAll();
        foreach ($categories as $key => $category) {
            $ratingLabel = new RatingLabel();
            $ratingLabel->setRating1('Качество работы')
                ->setCategory($category)
                ->setRating2('Уровень шума')
                ->setRating3("Цена/Качество")
                ->setRating4("Отзывы");
            $manager->persist($ratingLabel);
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
        return ['AppBundle\DataFixtures\ORM\LoadCategoryData'];
    }
}