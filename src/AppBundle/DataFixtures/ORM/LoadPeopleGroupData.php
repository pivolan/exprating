<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Feedback;
use AppBundle\Entity\Image;
use AppBundle\Entity\PeopleGroup;
use AppBundle\Entity\Product;
use AppBundle\Form\CommentType;
use Cocur\Slugify\Slugify;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadPeopleGroupData extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
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
        $names = [
            'Для женщин',
            'Для мужчин',
            'Для детей',
            'Для всех',
        ];
        /** @var Slugify $slugify */
        $slugify = $this->container->get('appbundle.slugify');
        foreach ($names as $name) {
            $peopleGroup = new PeopleGroup();
            $peopleGroup->setName($name)
                ->setSlug($slugify->slugify($name));
            $manager->persist($peopleGroup);
        }
        $manager->flush();
    }
}