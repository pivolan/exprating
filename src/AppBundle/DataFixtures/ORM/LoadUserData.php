<?php

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;

class LoadUserData extends AbstractFixture implements FixtureInterface
{
    const REFERENCE_ADMIN_USER = 'admin_user';

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
            ->setFullName('Admin Admin')
            ->setAvatarImage('avatar.jpg')
            ->addRole(User::ROLE_SUPER_ADMIN);

        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference(self::REFERENCE_ADMIN_USER, $userAdmin);
    }
}