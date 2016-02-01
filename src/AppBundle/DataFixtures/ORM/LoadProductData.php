<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadProductData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setTitle('title ' . $i)
                ->setMinPrice(rand(1.00, 1000.00))
                ->setRating(rand(1, 99))
                ->setSlug('product_' . $i)
                ->setPreviewImage('product_' . $i . '.jpg');
            $manager->persist($product);
        }
        $manager->flush();
    }
}