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
            ->setBirthday(new \DateTime('1987-02-08'))
            ->setFullName('Admin Admin')
            ->setCity('Москва')
            ->setCaption('Главный эксперт всея Руси ')
            ->setAvatarImage('http://placehold.it/200x200')
            ->addRole(User::ROLE_SUPER_ADMIN)
            ->addRole(User::ROLE_EXPERT);

        $manager->persist($userAdmin);
        $manager->flush();
        $this->addReference(self::REFERENCE_ADMIN_USER, $userAdmin);
    }
}