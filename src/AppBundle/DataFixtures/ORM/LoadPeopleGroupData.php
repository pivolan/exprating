<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\PeopleGroup;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPeopleGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    const DLYA_MUZHCHIN = 'dlya-muzhchin';
    const DLYA_ZHENSHCHIN = 'dlya-zhenshchin';
    const DLYA_DETEY = 'dlya-detey';
    const DLYA_VSEH = 'dlya-vseh';
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
        $names = [
            self::DLYA_MUZHCHIN   => 'Для женщин',
            self::DLYA_ZHENSHCHIN => 'Для мужчин',
            self::DLYA_DETEY      => 'Для детей',
            self::DLYA_VSEH       => 'Для всех',
        ];
        foreach ($names as $slug => $name) {
            $peopleGroup = new PeopleGroup();
            $peopleGroup->setName($name)
                ->setSlug($slug);
            $manager->persist($peopleGroup);
            $this->setReference($slug, $peopleGroup);
        }
        $manager->flush();
    }
}
