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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1987-02-08'))
            ->setFullName('Admin Admin')
            ->setCity('Москва')
            ->setCaption('Главный эксперт всея Руси ')
            ->addRole(User::ROLE_SUPER_ADMIN)
            ->addRole(User::ROLE_ADMIN)
            ->addRole(User::ROLE_EXPERT);
        $manager->persist($userAdmin);

        $curator = new User();
        $curator->setUsername('curator')
            ->setUsernameCanonical('curator')
            ->setEmail('curator@exprating.lo')
            ->setEmailCanonical('curator@exprating.lo')
            ->setPlainPassword('qwerty')
            ->setEnabled(true)
            ->setIsActivated(true)
            ->setCurator($userAdmin)
            ->setBirthday(new \DateTime('1986-02-08'))
            ->setFullName('Curator Curator')
            ->setCity('Москва')
            ->setCaption('Главный куратор')
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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert expert')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт')
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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert2 expert2')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт2')
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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert3 expert3')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт3')
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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('expert31 expert31')
            ->setCity('Москва')
            ->setCaption('Обычный Эксперт31')
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
            ->setIsActivated(true)
            ->setBirthday(new \DateTime('1985-02-08'))
            ->setFullName('category admin')
            ->setCity('Москва')
            ->setCaption('Эксперт Админ категорий')
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
