<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feedback;
use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadCommentData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
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
    public function getDependencies()
    {
        return [LoadProductData::class];
    }
}