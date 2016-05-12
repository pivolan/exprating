<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exprating\ImportXmlBundle\Entity\PartnerProduct;

class LoadPartnerProductData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $offer = (new PartnerProduct())
            ->setHash(md5('#$'))
            ->setAmount(123)
            ->setAvailable(true)
            ->setCategoryId(1)
            ->setCategoryName('Accessories')
            ->setCategoryPath('Qwe qwe qwe')
            ->setCompany('Mtc')
            ->setDescription("Some description with line break \n and next line ")
            ->setId(12)
            ->setMarketCategory('Cars')
            ->setName('Name')
            ->setOldPrice(233)
            ->setParams(['first'=>'value', 'second'=>'value'])
            ->setPictures(['http://placehold.it/100x100'])
            ->setPrice(100)
            ->setUrl('https://clothia.com/item/234')
            ->setVendor('Nike')
            ->setVendorCode(90)
            ->setYear(2016);
        $manager->persist($offer);
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
        return [LoadProductData::class];
    }
}
