<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadUserData extends AbstractFixture implements FixtureInterface
{
    const REFERENCE_ADMIN_USER = 'admin_user';
    const REFERENCE_CURATOR_USER = 'curator_user';
    const REFERENCE_EXPERT_USER = 'expert_user';

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
        $curator = new User();
        $curator->setUsername('curator')
            ->setUsernameCanonical('curator')
            ->setEmail('curator@exprating.lo')
            ->setEmailCanonical('curator@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1986-02-08'))
            ->setFullName('Curator Curator')
            ->setCity('Москва')
            ->setCaption('Главный куратор')
            ->setAvatarImage('http://placehold.it/201x201')
            ->addRole(User::ROLE_EXPERT_CURATOR)
            ->addRole(User::ROLE_EXPERT);

        $manager->persist($curator);
        $expert = new User();
        $expert->setUsername('expert')
            ->setUsernameCanonical('expert')
            ->setEmail('expert@exprating.lo')
            ->setEmailCanonical('expert@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setCurator($curator)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert expert')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт')
            ->setAvatarImage('http://placehold.it/202x202')
            ->addRole(User::ROLE_EXPERT);

        $manager->persist($expert);
        $manager->flush();
        $this->addReference(self::REFERENCE_ADMIN_USER, $userAdmin);
        $this->addReference(self::REFERENCE_CURATOR_USER, $curator);
        $this->addReference(self::REFERENCE_EXPERT_USER, $expert);
    }
}