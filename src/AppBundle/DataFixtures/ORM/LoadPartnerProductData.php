<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Exprating\ImportXmlBundle\Entity\PartnerProduct;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPartnerProductData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $faker = $this->container->get('service.faker');
        foreach (range(1, 1000) as $num) {
            $offer = (new PartnerProduct())
                ->setHash(md5("$num#$"))
                ->setAmount(rand(100, 1000))
                ->setAvailable(true)
                ->setCategoryId($num)
                ->setCategoryName($faker->name)
                ->setCategoryPath($faker->title)
                ->setCompany($faker->company)
                ->setDescription($faker->realText())
                ->setId($num)
                ->setMarketCategory($faker->word)
                ->setName($faker->streetName)
                ->setOldPrice(rand(100, 1000))
                ->setParams([$faker->word => $faker->word, 'color' => $faker->colorName, 'city' => $faker->city])
                ->setPictures([$faker->imageUrl()])
                ->setPrice(rand(100, 1000))
                ->setUrl($faker->url)
                ->setVendor($faker->company)
                ->setVendorCode(90)
                ->setYear($faker->year);
            $manager->persist($offer);
        }
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
