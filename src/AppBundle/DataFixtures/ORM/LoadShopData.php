<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Shop;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadShopData extends AbstractFixture implements FixtureInterface
{

    const REFERENCE_SHOP = 'shop_';

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 50; $i++) {
            $shop = new Shop();
            $shop->setName("Shop $i");
            $shop->setImage("http://placehold.it/150x70");
            $manager->persist($shop);
            $this->addReference(self::REFERENCE_SHOP . $i, $shop);
        }
        $manager->flush();
    }
}