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
    const REFERENCE_CATEGORY_ADMIN_USER = 'category_admin_user';

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
            ->setCurator($userAdmin)
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

        $expert2 = new User();
        $expert2->setUsername('expert2')
            ->setUsernameCanonical('expert2')
            ->setEmail('expert2@exprating.lo')
            ->setEmailCanonical('expert2@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setCurator($curator)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert2 expert2')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт2')
            ->setAvatarImage('http://placehold.it/203x203')
            ->addRole(User::ROLE_EXPERT);
        $manager->persist($expert2);

        $expert3 = new User();
        $expert3->setUsername('expert3')
            ->setUsernameCanonical('expert3')
            ->setEmail('expert3@exprating.lo')
            ->setEmailCanonical('expert3@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setCurator($curator)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert3 expert3')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт3')
            ->setAvatarImage('http://placehold.it/203x204')
            ->addRole(User::ROLE_EXPERT);
        $manager->persist($expert3);

        $expert31 = new User();
        $expert31->setUsername('expert31')
            ->setUsernameCanonical('expert31')
            ->setEmail('expert31@exprating.lo')
            ->setEmailCanonical('expert31@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setCurator($expert3)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert31 expert31')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт31')
            ->setAvatarImage('http://placehold.it/203x205')
            ->addRole(User::ROLE_EXPERT);
        $manager->persist($expert31);

        $categoryAdmin = new User();
        $categoryAdmin->setUsername('category')
            ->setUsernameCanonical('category')
            ->setEmail('category@exprating.lo')
            ->setEmailCanonical('category@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setCurator($userAdmin)
            ->setEnabled(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('category admin')
            ->setCity('Москва')
            ->setCaption('Эксперт Админ категорий')
            ->setAvatarImage('http://placehold.it/204x204')
            ->addRole(User::ROLE_EXPERT_CATEGORY_ADMIN)
            ->addRole(User::ROLE_EXPERT);
        $manager->persist($categoryAdmin);

        $manager->flush();
        $this->addReference(self::REFERENCE_ADMIN_USER, $userAdmin);
        $this->addReference(self::REFERENCE_CURATOR_USER, $curator);
        $this->addReference(self::REFERENCE_EXPERT_USER, $expert);
        $this->addReference(self::REFERENCE_CATEGORY_ADMIN_USER, $categoryAdmin);
    }
}