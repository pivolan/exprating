<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $userAdmin = new User();
        $userAdmin->setUsername('admin')
            ->setUsernameCanonical('admin')
            ->setEmail('admin@exprating.lo')
            ->setEmailCanonical('admin@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setSuperAdmin(true)
            ->setEnabled(true)
            ->addRole(User::ROLE_SUPER_ADMIN);

        $manager->persist($userAdmin);
        $manager->flush();
    }
}