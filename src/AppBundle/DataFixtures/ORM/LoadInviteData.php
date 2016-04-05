<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Image;
use AppBundle\Entity\Invite;
use AppBundle\Entity\Product;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadInviteData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var User $expert */
        $expert = $this->getReference(LoadUserData::REFERENCE_EXPERT_USER);
        $invite = (new Invite())
            ->setExpert($expert)
            ->setEmail($expert->getEmail())
            ->setCurator($this->getReference(LoadUserData::REFERENCE_CURATOR_USER))
            ->setIsActivated(true)
            ->setIsFromFeedback(true);
        $manager->persist($invite);
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
        return [LoadUserData::class];
    }
}
