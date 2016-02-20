<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadImageData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Product[] $products */
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $product) {
            for ($i = 1; $i <= 3; $i++) {
                $image = new Image();
                $image->setFilename('http://placehold.it/270x270?' . $product->getSlug() . "-$i.jpg")
                    ->setName($product->getName() . "-$i")
                    ->setProduct($product);
                if ($i == 1) {
                    $image->setIsMain(true);
                }
                $manager->persist($image);
                $product->addImage($image);
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