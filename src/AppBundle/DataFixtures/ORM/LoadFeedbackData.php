<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadFeedbackData extends AbstractFixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        /** @var Product[] $products */
        $products = $manager->getRepository('AppBundle:Product')->findAll();
        foreach ($products as $key => $product) {
            $commentsCount = ($key + 3) % 5;
            for ($i = 1; $i <= $commentsCount; $i++) {
                $feedback = new \AppBundle\Entity\Feedback();
                $feedback->setProduct($product)
                    ->setFullName('Чернова Ирина '.$key * $i)
                    ->setAdvantages('Качество. Сборка в Германии. Большое количество насадок. Работает тихо.')
                    ->setDisadvantages('Завышенная цена.')
                    ->setComment('У нас такая же модель, только с мешком для сбора пыли. Выбрали мешковой, т.к. в нем мощность немного выше. Пылесосом очень довольны. Все функции выполняет на 5 с плюсом! Работает тихо и качественно.');
                $manager->persist($feedback);
            }
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
        return ['AppBundle\DataFixtures\ORM\LoadProductData'];
    }
}
