<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Manufacturer;
use AppBundle\Entity\Shop;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadManufacturerData extends AbstractFixture implements FixtureInterface
{

    const REFERENCE_MANUFACTURER = 'manufacturer_';

    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i <= 10; $i++) {
            $shop = new Manufacturer();
            $shop->setName("Shop $i");
            $shop->setImage("http://placehold.it/150x58");
            $shop->setDescription('Немецкая группа компаний - один из ведущих мировых производителей в области автомобильных компонентов, потребительских товаров, строительных и упаковочных решений. ');
            $manager->persist($shop);
            $this->addReference(self::REFERENCE_MANUFACTURER . $i, $shop);
        }
        $manager->flush();
    }
}