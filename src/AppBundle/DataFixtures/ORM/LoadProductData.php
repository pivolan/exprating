<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadProductData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user = $this->getReference(LoadUserData::REFERENCE_ADMIN_USER);
        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setName('title ' . $i)
                ->setMinPrice(rand(1.00, 1000.00))
                ->setRating(rand(1, 99))
                ->setSlug('product_' . $i)
                ->setExpertUser($user)
                ->setPreviewImage('product_' . $i . '.jpg');

            $manager->persist($product);
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
        return ['AppBundle\DataFixtures\ORM\LoadUserData'];
    }
}