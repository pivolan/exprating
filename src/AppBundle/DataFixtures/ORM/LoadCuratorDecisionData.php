<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\CuratorDecision;
use AppBundle\Entity\Product;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use AppBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadCuratorDecisionData extends AbstractFixture implements DependentFixtureInterface, ContainerAwareInterface
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
        $faker = $this->container->get('service.faker');
        $expert = $this->getReference(LoadUserData::REFERENCE_EXPERT_USER);
        /** @var Product[] $products */
        $products = $manager->getRepository('AppBundle:Product')->findBy(['expertUser' => $expert], null);
        /** @var User $curator */
        $curator = $this->getReference(LoadUserData::REFERENCE_CURATOR_USER);
        foreach ($products as $key => $product) {
            $decisionsCount = ($key + 2) % 5;
            for ($i = 1; $i <= $decisionsCount; $i++) {
                $decision = new \AppBundle\Entity\CuratorDecision();
                $decision->setProduct($product)
                    ->setCurator($curator)
                    ->setStatus(CuratorDecision::STATUS_REJECT)
                    ->setRejectReason($faker->text);
                if ($i == $decisionsCount) {
                    if ($product->getIsEnabled()) {
                        $decision->setStatus(CuratorDecision::STATUS_APPROVE)
                            ->setRejectReason(null);
                    } elseif (rand(0, 1)) {
                        $decision->setStatus(CuratorDecision::STATUS_WAIT)
                            ->setRejectReason(null);
                    }
                }
                $manager->persist($decision);
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
        return [LoadProductData::class];
    }
}
